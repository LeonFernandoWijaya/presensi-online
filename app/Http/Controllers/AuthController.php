<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\Department;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'nik' => 'required|numeric',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirmpassword' => 'required|same:password',
            'department' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validationMessage' => $validator->errors()->toArray(),
                'message' => 'Make sure to fill all the fields.',
            ]);
        }

        $isNIKAlreadyUsed = User::where('NIK', $request->nik)->first();
        if ($isNIKAlreadyUsed && $isNIKAlreadyUsed->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'This NIK is already registered.',
            ]);
        }

        $isEmailAlreadyExist = User::where('email', $request->email)->first();
        if ($isEmailAlreadyExist && $isEmailAlreadyExist->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered.',
            ]);
        } else if ($isEmailAlreadyExist && !$isEmailAlreadyExist->email_verified_at) {
            $isEmailAlreadyExist->OTP = rand(100000, 999999);
            $isEmailAlreadyExist->NIK = $request->nik;
            $isEmailAlreadyExist->first_name = $request->firstname;
            $isEmailAlreadyExist->last_name = $request->lastname;
            $isEmailAlreadyExist->password = bcrypt($request->password);
            $isEmailAlreadyExist->role_id = 1;
            $isEmailAlreadyExist->department_id = $request->department;
            $isEmailAlreadyExist->save();
            $currentUserId = $isEmailAlreadyExist->id;
            Mail::to($isEmailAlreadyExist->email)->send(new OTPMail($isEmailAlreadyExist));
            return response()->json([
                'success' => true,
                'message' => 'Verification email has been sent to your email address.',
                'currentUserId' => $currentUserId,
            ]);
        } else {
            $user = new User();
            $user->first_name = $request->firstname;
            $user->last_name = $request->lastname;
            $user->email = $request->email;
            $user->NIK = $request->nik;
            $user->password = bcrypt($request->password);
            $user->role_id = 1;
            $user->department_id = $request->department;
            $user->OTP = rand(100000, 999999);
            $user->save();
            $currentUserId = $user->id;

            // Send verification email
            Mail::to($user->email)->send(new OTPMail($user));
            return response()->json([
                'success' => true,
                'message' => 'Verification email has been sent to your email address.',
                'currentUserId' => $currentUserId,
            ]);
        }
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

        $user = User::where('email', $request->email)->first();

        if ($user && !$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first.',
            ]);
        }

        if ($user) {
            // Delete existing session if exists
            Session::where('user_id', $user->id)->delete();
        }

        if (auth()->attempt($request->only('email', 'password'))) {
            Session::create([
                'user_id' => auth()->user()->id,
                'session_id' => session()->getId(),
            ]);

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
        // Get user's id
        $userId = auth()->user()->id;

        // Delete the user's session from the database
        Session::where('user_id', $userId)->delete();

        // Logout the user
        auth()->logout();

        return redirect('/login');
    }

    public function verifyEmail(Request $request)
    {
        $request->only('otp', 'currentUserId');
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'currentUserId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validationMessage' => $validator->errors()->toArray(),
                'message' => 'Make sure to fill all the fields.',
            ]);
        }

        $user = User::find($request->currentUserId);
        if ($user && $user->email_verified_at == null) {
            if ($user->OTP == $request->otp) {
                $user->email_verified_at = now();
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified.',
            ]);
        }
    }
}
