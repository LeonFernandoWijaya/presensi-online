<?php

namespace App\Http\Controllers;

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
}
