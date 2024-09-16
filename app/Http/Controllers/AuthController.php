<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // login
    public function showSignup()
    {
        $navbar = null;
        return view('signup', compact('navbar'));
    }

    public function showLogin()
    {
        $navbar = null;
        return view('login', compact('navbar'));
    }
}
