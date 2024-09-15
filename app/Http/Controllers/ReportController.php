<?php

namespace App\Http\Controllers;

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

        if (!empty($request->staff_id)) {
            $staffId = $request->staff_id;
            if ($staffId != '111') {
                $sampleData = array_filter($sampleData, function ($data) use ($staffId) {
                    return $data['staff_id'] == $staffId;
                });
            }
        }

        $sampleData = array_values($sampleData);

        return response()->json($sampleData, 200);
    }
}
