<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RejectExport;

class RejectController extends Controller
{
    // done authotize
    public function index()
    {
        if (Gate::allows('isManager')) {
            $user = Auth::user();
            $navbar = 'reject';
            $users = User::where('department_id', $user->department_id)->get();
            return view('reject', compact('navbar', 'users'));
        } else {
            abort(403);
        }
    }

    // done authotize
    public function getOvertimeForReject(Request $request)
    {
        $user = Auth::user();
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $departmentName = $user->department->department_name;

        $overtime = Overtime::when($staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
            ->when($startDate, function ($query, $startDate) {
                return $query->where('overtimeStart', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->where('overtimeEnd', '<=', $endDate);
            })
            ->where('rejectDate', null)
            ->whereHas('user.department', function ($query) use ($departmentName) {
                $query->where('department_name', $departmentName);
            })
            ->with('user', 'attendance')
            ->paginate(10);

        // Get all overtime IDs matching the same criteria
        $overtimeIds = Overtime::when($staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
            ->when($startDate, function ($query, $startDate) {
                return $query->where('overtimeStart', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->where('overtimeEnd', '<=', $endDate);
            })
            ->where('rejectDate', null)
            ->whereHas('user.department', function ($query) use ($departmentName) {
                $query->where('department_name', $departmentName);
            })
            ->pluck('id');


        return response()->json(['overtime' => $overtime, 'overtimeIds' => $overtimeIds]);
    }

    // done authotize
    public function rejectOvertime(Request $request)
    {
        $overtime = Overtime::find($request->id);

        if (Gate::allows('rejectOvertime', $overtime)) {
            $overtime->rejectDate = date('Y-m-d H:i:s');
            $overtime->save();
            return response()->json(['success' => true, 'message' => 'Overtime has been rejected']);
        } else {
            abort(403);
        }

        return response()->json(['success' => true, 'message' => 'Overtime has been rejected']);
    }


    // done authotize
    public function rejectSelectedOvertime(Request $request)
    {
        $overtimes = Overtime::whereIn('id', $request->selectedOvertime)->get();
        foreach ($overtimes as $overtime) {
            if (Gate::allows('rejectOvertime', $overtime) && $overtime->rejectDate == null) {
                $overtime->rejectDate = date('Y-m-d H:i:s');
                $overtime->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Overtimes have been rejected']);
    }

    public function downloadReject(Request $request)
    {
        if (Gate::allows('isManager')) {
            $staffId = $request->staff;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $departmentName = Auth::user()->department->department_name;

            return Excel::download(new RejectExport($staffId, $startDate, $endDate, $departmentName), 'reject.xlsx');
        } else {
            abort(403);
        }
    }
}
