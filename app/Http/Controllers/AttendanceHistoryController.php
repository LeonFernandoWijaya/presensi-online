<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceHistoryController extends Controller
{
    //
    public function index()
    {
        $navbar = 'history';
        $roleId = Auth::user()->role_id;
        $userResults = null;

        if ($roleId == 1) {
            // Store the user themselves in the result variable
            $userResults = User::where('id', Auth::user()->id)->get();
        } elseif ($roleId == 2) {
            // Store all users in the result variable
            $userResults = User::all();
        }

        return view('attendance-history', compact('navbar', 'userResults'));
    }

    public function getAttendanceHistory(Request $request)
    {
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
    
        if ($staffId) {
            $user = User::find($staffId);
            $attendance = $user->presences()
                ->with('user')
                ->when($startDate, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($endDate, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                ->paginate(5);
        } else {
            $attendance = Attendance::with('user')
                ->when($startDate, function ($query, $startDate) {
                    return $query->where('date', '>=', $startDate);
                })
                ->when($endDate, function ($query, $endDate) {
                    return $query->where('date', '<=', $endDate);
                })
                ->paginate(5);
        }
    
        return response()->json($attendance);
    }

    public function getAttendanceDetail(Request $request)
    {
        $attendanceId = $request->id;
        $attendance = Attendance::with('user')->find($attendanceId);
    
        return response()->json($attendance);
    }
}
