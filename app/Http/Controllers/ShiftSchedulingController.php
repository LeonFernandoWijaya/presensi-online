<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftScheduling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ScheduleImport;


class ShiftSchedulingController extends Controller
{
    //
    public function index()
    {
        $navbar = 'settings';
        $users = User::whereNotNull('email_verified_at')
            ->orderBy('role_id', 'asc')
            ->get();
        $shifts = Shift::all();
        return view('shift-scheduling', ['navbar' => $navbar, 'users' => $users, 'shifts' => $shifts]);
    }

    public function saveNewSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'shiftId' => 'required|integer',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Please filled all forms'], 200);
        }

        if ($request->startDate > $request->endDate) {
            return response()->json(['success' => false, 'message' => 'Start date must be less than end date'], 200);
        }

        // Find overlapping schedule for the user
        $findSchedule = ShiftScheduling::where('user_id', $request->userId)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->startDate, $request->endDate])
                    ->orWhereBetween('end_date', [$request->startDate, $request->endDate])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->startDate)
                            ->where('end_date', '>=', $request->endDate);
                    });
            })
            ->exists();

        if ($findSchedule) {
            return response()->json(['success' => false, 'message' => 'There is an overlapping schedule for this user'], 200);
        }

        $shiftScheduling = new ShiftScheduling();
        $shiftScheduling->user_id = $request->userId;
        $shiftScheduling->shift_id = $request->shiftId;
        $shiftScheduling->start_date = $request->startDate;
        $shiftScheduling->end_date = $request->endDate;
        $shiftScheduling->save();

        return response()->json(['success' => true, 'message' => 'Schedule saved successfully'], 200);
    }

    public function getShiftSchedule(Request $request)
    {
        if ($request->userId === null) {
            $shiftSchedules = ShiftScheduling::with('user', 'shift')->orderBy('user_id')->orderBy('start_date', 'asc')->paginate(10);
        } else {
            $shiftSchedules = ShiftScheduling::with('user', 'shift')->where('user_id', $request->userId)->orderBy('user_id')->orderBy('start_date', 'asc')->paginate(10);
        }

        return response()->json($shiftSchedules);
    }

    public function getShiftScheduleDetail(Request $request)
    {
        $shiftSchedule = ShiftScheduling::with('user', 'shift')->find($request->id);
        return response()->json($shiftSchedule);
    }

    public function updateSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'shiftId' => 'required|integer',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Please filled all forms'], 200);
        }

        if ($request->startDate > $request->endDate) {
            return response()->json(['success' => false, 'message' => 'Start date must be less than end date'], 200);
        }

        // Find overlapping schedule for the user
        $findSchedule = ShiftScheduling::where('user_id', $request->userId)
            ->where('id', '!=', $request->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->startDate, $request->endDate])
                    ->orWhereBetween('end_date', [$request->startDate, $request->endDate])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->startDate)
                            ->where('end_date', '>=', $request->endDate);
                    });
            })
            ->exists();

        if ($findSchedule) {
            return response()->json(['success' => false, 'message' => 'There is an overlapping schedule for this user'], 200);
        }

        $shiftScheduling = ShiftScheduling::find($request->id);
        $shiftScheduling->user_id = $request->userId;
        $shiftScheduling->shift_id = $request->shiftId;
        $shiftScheduling->start_date = $request->startDate;
        $shiftScheduling->end_date = $request->endDate;
        $shiftScheduling->save();

        return response()->json(['success' => true, 'message' => 'Schedule updated successfully'], 200);
    }

    public function deleteSchedule(Request $request)
    {
        $shiftScheduling = ShiftScheduling::find($request->id);
        $shiftScheduling->delete();

        return response()->json(['success' => true, 'message' => 'Schedule deleted successfully'], 200);
    }

    public function importNow(Request $request)
    {
        $validImport = 0;
        $invalidImport = [];

        $file = $request->file('file');
        if (!$file) {
            return response()->json(['success' => false ,'message' => 'Please upload a file'], 200);
        }

        // check file format xlsx
        $fileExtension = $file->getClientOriginalExtension();
        if ($fileExtension != 'xlsx') {
            return response()->json(['success' => false ,'message' => 'Please upload a file with xlsx format'], 200);
        }
        // Baca data dari file Excel
        $data = Excel::toArray(new ScheduleImport, $file);
        // Looping data Excel
        foreach (array_slice($data[0], 1) as $key => $row) {
            // Validasi data
            $messages = [
                '0.required' => 'User ID field is required.',
                '1.required' => 'Shift ID field is required.',
                '2.required' => 'Start date field is required.',
                '2.date' => 'Start date must be a valid date.',
                '3.required' => 'End date field is required.',
                '3.date' => 'End date must be a valid date.',
            ];

            $validator = Validator::make($row, [
                '0' => 'required',
                '1' => 'required',
                '2' => 'required|date',
                '3' => 'required|date',
            ], $messages);

            // Jika validasi gagal
            if ($validator->fails()) {
                $invalidImport[] = [
                    'row' => $key + 2,
                    'data' => $row,
                    'errors' => $validator->errors()->first()
                ];
            } else {
                $findSchedule = ShiftScheduling::where('user_id', $row[0])
                    ->where(function ($query) use ($row) {
                        $query->whereBetween('start_date', [$row[2], $row[3]])
                            ->orWhereBetween('end_date', [$row[2], $row[3]])
                            ->orWhere(function ($query) use ($row) {
                                $query->where('start_date', '<=', $row[2])
                                    ->where('end_date', '>=', $row[3]);
                            });
                    })
                    ->exists();
                if ($findSchedule) {
                    $invalidImport[] = [
                        'row' => $key + 2,
                        'data' => $row,
                        'errors' => 'There is an overlapping schedule for this user'
                    ];
                } else {
                    $shiftScheduling = new ShiftScheduling();
                    $shiftScheduling->user_id = $row[0];
                    $shiftScheduling->shift_id = $row[1];
                    $shiftScheduling->start_date = $row[2];
                    $shiftScheduling->end_date = $row[3];
                    $shiftScheduling->save();
                    $validImport++;
                }
            }
        }
    

        $totalData = count($data[0]) - 1;
        $totalValid = $validImport;
        $totalInvalid = count($invalidImport);

        // Kembalikan data dalam format JSON
        return response()->json(['success' => true, 'invalidImport' => $invalidImport, 'totalData' => $totalData, 'totalValid' => $totalValid, 'totalInvalid' => $totalInvalid]);
    }

}
