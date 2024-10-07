<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Gate;

class AttendanceHistoryController extends Controller
{
    // done authotize
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

    // done authotize
    public function getAttendanceHistory(Request $request)
    {
        $user = Auth::user();
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $attendance = Attendance::when($staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
            ->when($startDate, function ($query, $startDate) {
                return $query->where('clockInTime', '>=', $startDate . ' 00:00:00');
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->where('clockOutTime', '<=', $endDate . ' 23:59:59');
            })
            ->when($user->role->id == 1, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->with('user', 'activitytype', 'activitycategory')
            ->paginate(5);

        return response()->json($attendance);
    }

    // done authotize
    public function getAttendanceDetail(Request $request)
    {
        $attendanceId = $request->id;
        $attendance = Attendance::with('user', 'activitytype', 'activitycategory')->find($attendanceId);

        if (Gate::allows('viewAttendance', $attendance)) {
            return response()->json($attendance);
        } else {
            abort(403);
        }
    }

    // done authotize
    public function downloadAttendanceHistory(Request $request)
    {
        if (Gate::allows('isManager')) {
            $staffId = $request->staffId;
            $startDate = $request->startDate;
            $endDate = $request->endDate;

            return Excel::download(new AttendanceExport($staffId, $startDate, $endDate), 'attendance.xlsx');
        } else {
            abort(403);
        }
    }
}
