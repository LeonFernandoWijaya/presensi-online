<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OvertimeExport;
use Illuminate\Support\Facades\Gate;

class OvertimeHistoryController extends Controller
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

        return view('overtime-history', compact('navbar', 'userResults'));
    }

    // done authotize
    public function getOvertimeHistory(Request $request)
    {
        $user = Auth::user();
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
        $overtimeType = $request->overtimeType;

        $overtime = Overtime::when($staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
            ->when($startDate, function ($query, $startDate) {
                return $query->where('overtimeStart', '>=', $startDate . ' 00:00:00');
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->where('overtimeEnd', '<=', $endDate . ' 23:59:59');
            })
            ->when($status == 1, function ($query) {
                return $query->whereNull('rejectDate');
            })
            ->when($status == 2, function ($query) {
                return $query->whereNotNull('rejectDate');
            })
            ->when($user->role->id == 1, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->when($overtimeType == 1, function ($query) {
                return $query->whereNotNull('attendance_id');
            })
            ->when($overtimeType == 2, function ($query) {
                return $query->whereNull('attendance_id');
            })
            ->with('user', 'attendance')
            ->orderBy('overtimeStart', 'desc')
            ->paginate(10);

        return response()->json($overtime);
    }

    // done authotize
    public function getOvertimeDetail(Request $request)
    {
        $overtime = Overtime::where('id', $request->id)->with('user', 'attendance', 'attendance.activitytypeclockin', 'attendance.activitycategoryclockin', 'attendance.activitytypeclockout', 'attendance.activitycategoryclockout')->first();

        if (Gate::allows('viewOvertime', $overtime)) {
            return response()->json($overtime);
        } else {
            abort(403);
        }
    }

    //done authotize
    public function downloadOvertimeHistory(Request $request)
    {
        if (Gate::allows('isManager')) {
            $staffId = $request->staff;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $status = $request->status;
            $overtimeType = $request->overtimeType;

            return Excel::download(new OvertimeExport($staffId, $startDate, $endDate, $status, $overtimeType), 'overtime.xlsx');
        } else {
            abort(403);
        }
    }
}
