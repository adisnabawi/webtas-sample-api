<?php

namespace App\Http\Controllers;

use App\TimeIn;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // type(1=hadir,2=takhadir,3=cuti,4=pelulus)
        $sampleData = [
            [
                'name' => 'John Doe',
                'staff_id' => '12345',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '3F Office, Ground Floor',
                'time_in' => '8:00 AM',
                'time_out' => '5:00 PM',
                'late' => '00:00',
                'time_in_status' => 'On Time',
                'type' => 1,
            ],
            [
                'name' => 'Jane Doe',
                'staff_id' => '12346',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '3F Office, Ground Floor',
                'time_in' => '8:00 AM',
                'time_out' => '5:00 PM',
                'late' => '00:00',
                'time_in_status' => 'On Time',
                'type' => 1,
            ],
            [
                'name' => 'John Doe',
                'staff_id' => '12345',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '3F Office, Ground Floor',
                'time_in' => '8:00 AM',
                'time_out' => '5:00 PM',
                'late' => '00:00',
                'time_in_status' => 'On Time',
                'type' => 1,
            ],
            [
                'name' => 'John Doe',
                'staff_id' => '12345',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '3F Office, Ground Floor',
                'time_in' => '8:00 AM',
                'time_out' => '5:00 PM',
                'late' => '00:00',
                'time_in_status' => 'On Time',
                'type' => 1,
            ],
            [
                'name' => 'John Doe',
                'staff_id' => '12345',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '3F Office, Ground Floor',
                'time_in' => '8:00 AM',
                'time_out' => '5:00 PM',
                'late' => '00:00',
                'time_in_status' => 'On Time',
                'type' => 1,
            ],
            [
                'name' => 'Juan Dela Cruz',
                'staff_id' => '12347',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '',
                'time_in' => '',
                'time_out' => '',
                'late' => '',
                'time_in_status' => '',
                'type' => 2,
            ],
            [
                'name' => 'Maria Clara',
                'staff_id' => '12348',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '',
                'time_in' => '',
                'time_out' => '',
                'late' => '',
                'time_in_status' => '',
                'type' => 3,
            ],
            [
                'name' => 'Pedro Penduko',
                'staff_id' => '12349',
                'department' => 'IT',
                'unit' => 'Web Development',
                'working_hours' => 'WBF',
                'position' => 'Web Developer',
                'location_time_in' => '3F Office, Ground Floor',
                'time_in' => '8:00 AM',
                'time_out' => '5:00 PM',
                'late' => '00:00',
                'time_in_status' => 'On Time',
                'type' => 4,
            ],
        ];

        if (!empty($request->type)) {
            $type = $request->type;
            $sampleData = array_filter($sampleData, function ($data) use ($type) {
                return $data['type'] == $type;
            });
        }
        $sampleData = array_values($sampleData);
        return response()->json($sampleData, 200);
    }

    public function attendance(Request $request)
    {
        // Disable this for testing
        // if (empty($request->staff_id)) {
        //     return response()->json([
        //         'message' => 'Staff ID is required',
        //     ], 400);
        // }
        $type = config('staticdata.history.type.attendance');
        if (!empty($request->type)) {
            $type = $request->type;
        }
        $user = $request->user;
        $history = TimeIn::where('user_id', $user->id)
            ->where('type', $type)
            ->orderBy('time_in', 'desc')
            ->first();
        if ($request->type == config('staticdata.history.type.attendance')) {
            $sampleData = [
                'time_in_setting' => '9:00 AM',
                'time_out_setting' => '11:10 PM',
                'today_status' => 'Hadir',
                'location' => [
                    [
                        'latitude' => '3.2407552',
                        'longitude' => '101.7184256',
                        'distance' => '0.5', // 100 meters
                    ],
                    [
                        'latitude' => '3.234193',
                        'longitude' => '101.715760',
                        'distance' => '0.2', // 200 meters
                    ],
                    [
                        'latitude' => '3.2507552',
                        'longitude' => '101.7284256',
                        'distance' => '0.2', // 200 meters
                    ],
                    [
                        'latitude' => '37.4219983',
                        'longitude' => '-122.084',
                        'distance' => '0.1', // 100 meters
                    ]
                ],
                'previous_history' => [
                    'date' => $history ? Carbon::parse($history->time_in)->format('Y-m-d') : null,
                    'time_in' => $history ? Carbon::parse($history->time_in)->format('g:i A') : null,
                    'time_out' => $history ? Carbon::parse($history->time_out)->format('g:i A') : null,
                    'status' => 'Awal',
                ],
            ];

            return response()->json($sampleData, 200);
        }


        $sampleData = [
            'time_in_setting' => '8:00 AM',
            'time_out_setting' => '5:00 PM',
            'today_status' => 'Hadir',
            'location' => [
                [
                    'latitude' => '3.2407552',
                    'longitude' => '101.7184256',
                    'distance' => '0.5', // 500 meters
                ],
                [
                    'latitude' => '3.2507552',
                    'longitude' => '101.7284256',
                    'distance' => '0.2', // 200 meters
                ],
            ],
            'previous_history' => [
                'date' => $history ? Carbon::parse($history->time_in)->format('Y-m-d') : null,
                'time_in' => $history ? Carbon::parse($history->time_in)->format('g:i A') : null,
                'time_out' => $history->time_out ? Carbon::parse($history->time_out)->format('g:i A') : null,
                'status' => 'Lambat',
            ],
        ];

        return response()->json($sampleData, 200);
    }
}
