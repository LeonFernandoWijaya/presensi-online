<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OvertimeHistoryController extends Controller
{
    //
    public function index()
    {
        $navbar = 'history';
        return view('overtime-history', compact('navbar'));
    }
}
