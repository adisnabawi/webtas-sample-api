<?php

namespace App\Http\Controllers;

use App\TimeIn;
use App\TimeInImage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        $user = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($user) {
            $loginUser = User::find(Auth::user()->id);
            $loginUser->token = Str::random(50);
            $loginUser->save();

            $latestHistory = TimeIn::where('user_id', $loginUser->id)
                ->orderBy('time_in', 'desc')
                ->first();
            $loginUser->hasCompleted = false;
            if ($latestHistory && $latestHistory->time_out != null) {
                if (Carbon::parse($latestHistory->time_in)->isToday()) {
                    $loginUser->hasCompleted = false;
                }
                $latestHistory = null;
            }

            $loginUser->latestTimeIn = $latestHistory;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => $loginUser
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Login failed',
                'data' => []
            ]);
        }
    }

    public function logout($token)
    {
        $user = User::where('token', $token)->first();
        if ($user) {
            $user->token = null;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful',
                'data' => []
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Not able to find user',
                'data' => []
            ]);
        }
    }

    public function history($token, Request $request)
    {
        \Log::info('History Request', $request->all());
        $user = $request->user;
        $history = TimeIn::with('images')->where('user_id', $user->id);

        if ($request->has('type') && !empty($request->type)) {
            $history->where('type', $request->type);
        }

        if ($request->has('q') && !empty($request->q)) {
            $history->where(function ($query) use ($request) {
                $query->where('place_in', 'like', '%' . $request->q . '%')
                    ->orWhere('place_out', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->has('month') && $request->has('year') && !empty($request->month) && !empty($request->year)) {
            $start = Carbon::createFromDate($request->year, $this->getMonth($request->month), 1)->startOfMonth();
            $end = Carbon::createFromDate($request->year, $this->getMonth($request->month), 1)->endOfMonth();
           $history->where('date', '>=', $start->format('Y-m-d'))->where('date', '<=', $end->format('Y-m-d'));
           // $history->whereBetween('date', [$start, $end]);
        }

        if (!empty($request->limit)) {
            $history = $history->orderBy('time_in', 'desc')->limit($request->limit)->get();
        } else {
            $history = $history->orderBy('time_in', 'desc')->limit(50)->get();
        }

        $history = $history->map(function ($item) {
            $item->date = Carbon::parse($item->date)->format('d/m/Y');
            return $item;
        });
        $array['data'] = $history;
        $array['status'] = 'success';
        $array['message'] = 'History retrieved successfully';
        $json = json_encode($array);
        \Log::info('History Data', $array);
        return response($json, 200, ['Content-Type' => 'application/json']);
    }

    public function timein(Request $request, $token)
    {
        $user = $request->user;
        if ($user) {
            $timein = TimeIn::where('user_id', $user->id)
                ->whereDate('time_in', date('Y-m-d'))
                ->where('type', $request->type ?? config('staticdata.history.type.overtime'))
                ->whereNull('time_out')
                ->first();
            if ($timein) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('images', $imageName, 'public');
                    $time_in_image = TimeInImage::firstOrCreate([
                        'time_in_id' => $timein->id,
                        'name' => $imageName,
                        'url' => url(Storage::url($path)),
                    ]);
                }
                $timein->time_out = now();
                $timein->latitude_out = $request->latitude;
                $timein->longitude_out = $request->longitude;
                $timein->place_out = !empty($request->place) ? $request->place : '';
                $timein->remark_out = !empty($request->remark) ? $request->remark : '';
                $timein->type = $request->type ?? config('staticdata.history.type.overtime');
                $timein->save();
                $timein->hasCompleted = false;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Masa keluar berjaya direkod',
                    'data' => $timein
                ]);
            }

            $timein = TimeIn::create([
                'time_in' => now(),
                'user_id' => $user->id,
                'latitude_in' => $request->latitude,
                'longitude_in' => $request->longitude,
                'date' => now()->format('Y-m-d'),
                'type' => $request->type ?? config('staticdata.history.type.overtime'),
                'place_in' => !empty($request->place) ? $request->place : '',
                'remark' => !empty($request->remark) ? $request->remark : '',
            ]);
            try {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('images', $imageName, 'public');
                    $time_in_image = TimeInImage::firstOrCreate([
                        'time_in_id' => $timein->id,
                        'name' => $imageName,
                        'url' => url(Storage::url($path)),
                    ]);
                }
            } catch (\Throwable $th) {
                \Log::error('Time In Upload Image ERROR' . $th->getMessage());
            }
            $timein->hasCompleted = false;
            return response()->json([
                'status' => 'success',
                'message' => 'Masa masuk berjaya direkod',
                'data' => $timein
            ]);
        }
    }

    public function profile($token, Request $request)
    {
        $user = $request->user;
        $latestHistory = TimeIn::where('user_id', $user->id)
            ->orderBy('time_in', 'desc')
            ->first();
        $user->hasCompleted = false;
        if ($latestHistory && $latestHistory->time_out != null) {
            if (Carbon::parse($latestHistory->time_in)->isToday()) {
                $user->hasCompleted = false;
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Profile retrieved successfully',
            'data' => $user
        ]);
    }

    public function reportDownload($token, Request $request)
    {
        $user = User::with('historyLimit')->where('token', $token)
            ->whereNotNull('token')
            ->first();

        $pdf = PDF::loadView('exportpdf', $user->toArray());
        return response($pdf->stream('report_ot.pdf'), 200, ['Content-Type: application/pdf', 'Content-Disposition: attachment; filename="downloaded.pdf"']);
        // THIS IS THE CSV IMPLEMENTATION
        // $history = $user->historyLimit;
        // $fileName = 'report_ot.csv';

        // $headers = array(
        //     "Content-type"        => "text/csv",
        //     "Content-Disposition" => "attachment; filename=$fileName",
        //     "Pragma"              => "no-cache",
        //     "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        //     "Expires"             => "0"
        // );

        // $columns = array('Masa Masuk', 'Masa Keluar', 'Lokasi Masuk', 'Lokasi Keluar');

        // $callback = function () use ($history, $columns) {
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columns);

        //     foreach ($history as $task) {
        //         $row['Masa Masuk']  = $task->time_in;
        //         $row['Masa Keluar']    = $task->time_out;
        //         $row['Lokasi Masuk']    = 'Latitude: ' . $task->latitude_in . ', ' . 'Longitude: ' . $task->longitude_in;
        //         $row['Lokasi Keluar']  = 'Latitude: ' . $task->latitude_out . ', ' . 'Longitude: ' . $task->longitude_out;

        //         fputcsv($file, array($row['Masa Masuk'], $row['Masa Keluar'], $row['Lokasi Masuk'], $row['Lokasi Keluar']));
        //     }

        //     fclose($file);
        // };

        // return response()->stream($callback, 200, $headers);
    }

    public function uploadPicture(Request $request)
    {
        try {
            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
                'id' => 'required|exists:time_ins,id',
                'token' => 'required|exists:users,token'
            ]);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images', $imageName, 'public');
                $time_in_image = TimeInImage::firstOrCreate([
                    'time_in_id' => $request->id,
                    'name' => $imageName,
                    'url' => url(Storage::url($path)),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Upload picture successfull',
                    'data' => $time_in_image
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function maps(Request $request)
    {
        $rules = [
            'location' => 'required',
            'token' => 'required|exists:users,token'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $location = $request->location;
        return view('map', compact('location'));
    }

    public function places(Request $request)
    {
        $rules = [
            'location' => 'required',
            'token' => 'required|exists:users,token'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $location = $request->location;

        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json?latlng=$location&sensor=true&key=" . env('GOOGLE_API_KEY'));
        return $response->json();
    }

    public function getMonth($month)
    {
        switch ($month) {
            case 'Januari':
                return '01';
                break;
            case 'Februari':
                return '02';
                break;
            case 'Mac':
                return '03';
                break;
            case 'April':
                return '04';
                break;
            case 'Mei':
                return '05';
                break;
            case 'Jun':
                return '06';
                break;
            case 'Julai':
                return '07';
                break;
            case 'Ogos':
                return '08';
                break;
            case 'September':
                return  '09';
                break;
            case 'Oktober':
                return '10';
                break;
            case 'November':
                return '11';
                break;
            case 'Disember':
                return '12';
                break;
        }
    }

    public function webview($token)
    {
        $user = User::where('token', $token)->first();
        return view('webview', compact('user'));
    }
}
