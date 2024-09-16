<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $navbar = 'user';
        return view('user', compact('navbar'));
    }
}
