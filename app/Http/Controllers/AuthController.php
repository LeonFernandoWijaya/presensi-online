<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // login
    public function signUp()
    {
        $navbar = null;
        $departments = Department::all()->filter(function ($department) {
            return $department->department_name !== 'Human Resources';
        });

        return view('signup', compact('navbar', 'departments'));
    }

    public function login()
    {
        $navbar = null;
        return view('login', compact('navbar'));
    }

    public function storeSignUp(Request $request)
    {
        $request->only('firstname', 'lastname', 'email', 'password', 'department', 'confirmpassword');
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirmpassword' => 'required|same:password',
            'department' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validationMessage' => $validator->errors()->toArray(),
            ]);
        }

        $user = new User();
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = 1;
        $user->department_id = $request->department;
        $user->save();

        // Kirim email verifikasi
        // $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully. Wait for the admin to verify your account.',
        ]);
    }

    public function storeLogin(Request $request)
    {
        $request->only('email', 'password');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validationMessage' => $validator->errors()->toArray(),
            ]);
        }

        if (auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => true,
                'message' => 'Login success',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Login failed',
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}
