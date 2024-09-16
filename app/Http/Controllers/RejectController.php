<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RejectController extends Controller
{
    //
    public function index()
    {
        $navbar = 'reject';
        return view('reject', compact('navbar'));
    }
}
