<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OvertimeExport;

class OvertimeHistoryController extends Controller
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
        
        return view('overtime-history', compact('navbar', 'userResults'));
    }

    public function getOvertimeHistory(Request $request){
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
    
        $overtime = Overtime::when($staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
        ->when($startDate, function ($query, $startDate) {
            return $query->where('overtimeStart', '>=', $startDate);
        })
        ->when($endDate, function ($query, $endDate) {
            return $query->where('overtimeEnd', '<=', $endDate);
        })
        ->when($status == 1, function ($query) {
            return $query->whereNull('rejectDate');
        })
        ->when($status == 2, function ($query) {
            return $query->whereNotNull('rejectDate');
        })
        ->with('user', 'attendance')
        ->paginate(10);
    
        return response()->json($overtime);
    }

    public function getOvertimeDetail(Request $request){
        $overtime = Overtime::where('id', $request->id)->with('user', 'attendance')->first();
        return response()->json($overtime);
    }

    public function downloadOvertimeHistory(Request $request){
        $staffId = $request->staff;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
    
        $overtime = Overtime::when($staffId, function ($query, $staffId) {
            return $query->where('user_id', $staffId);
        })
        ->when($startDate, function ($query, $startDate) {
            return $query->where('overtimeStart', '>=', $startDate);
        })
        ->when($endDate, function ($query, $endDate) {
            return $query->where('overtimeEnd', '<=', $endDate);
        })
        ->when($status == 1, function ($query) {
            return $query->whereNull('rejectDate');
        })
        ->when($status == 2, function ($query) {
            return $query->whereNotNull('rejectDate');
        })
        ->with('user', 'attendance')
        ->get();
    
        return Excel::download(new OvertimeExport($overtime), 'overtime.xlsx');
    }
}
