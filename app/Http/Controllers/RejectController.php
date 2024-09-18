<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;

class RejectController extends Controller
{
    //
    public function index()
    {
        $navbar = 'reject';
        $users = User::all();
        return view('reject', compact('navbar', 'users'));
    }

    public function getOvertimeForReject(Request $request)
    {
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

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
            ->with('user')
            ->paginate(2);

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
            ->pluck('id');


        return response()->json(['overtime' => $overtime, 'overtimeIds' => $overtimeIds]);
    }

    public function rejectOvertime(Request $request)
    {
        $overtime = Overtime::find($request->id);
        $overtime->rejectDate = date('Y-m-d H:i:s');
        $overtime->save();

        return response()->json(['success' => true, 'message' => 'Overtime has been rejected']);
    }

    public function rejectSelectedOvertime(Request $request)
    {
        $overtimes = Overtime::whereIn('id', $request->selectedOvertime)->get();
        foreach ($overtimes as $overtime) {
            if ($overtime->rejectDate == null) {
                $overtime->rejectDate = date('Y-m-d H:i:s');
                $overtime->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Overtimes have been rejected']);
    }
}
