<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftScheduling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
