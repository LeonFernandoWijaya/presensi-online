<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestController extends Controller
{
    //
    public function index()
    {
        $navbar = 'request';
        return view('request', compact('navbar'));
    }
}
