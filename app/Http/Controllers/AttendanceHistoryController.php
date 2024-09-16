<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceHistoryController extends Controller
{
    //
    public function index()
    {
        $navbar = 'history';
        return view('attendance-history', compact('navbar'));
    }
}
