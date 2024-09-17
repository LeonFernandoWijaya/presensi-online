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
            ->paginate(10);


        return response()->json($overtime);
    }
}
