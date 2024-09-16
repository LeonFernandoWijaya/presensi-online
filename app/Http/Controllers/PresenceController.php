<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresenceController extends Controller
{
    //
    public function index()
    {
        $navbar = 'presence';
        return view('presence', compact('navbar'));
    }
}
