<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Holiday;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function index()
    {
        if (Gate::allows('isManager')) {
            $navbar = 'settings';
            return view('user', compact('navbar'));
        } else {
            abort(403);
        }
    }

    public function getInitialDataModal()
    {
        if (Gate::allows('isManager')) {
            $departments = Department::all();
            $roles = Role::all();
            $shifts = Shift::all();
            $holidays = Holiday::all();
            return response()->json([
                'departments' => $departments,
                'roles' => $roles,
                'shifts' => $shifts,
                'holidays' => $holidays
            ]);
        } else {
            abort(403);
        }
    }

    public function getAllUsers(Request $request)
    {
        if (Gate::allows('isManager')) {
            $searchName = $request->name;
            $accountStatus = $request->statusAccount;
            $users = User::with('department', 'role', 'shift')
                ->where(function ($query) use ($searchName) {
                    $query->where('first_name', 'like', '%' . $searchName . '%')
                        ->orWhere('last_name', 'like', '%' . $searchName . '%');
                })
                ->when($accountStatus !== null, function ($query) use ($accountStatus) {
                    return $query->where('is_active', $accountStatus);
                })
                ->paginate(10);
            return response()->json($users);
        } else {
            abort(403);
        }
    }

    public function getUserById(Request $request)
    {
        if (Gate::allows('isManager')) {
            $id = $request->id;
            $user = User::with('department', 'role', 'shift', 'holiday')->find($id);
            return response()->json($user);
        } else {
            abort(403);
        }
    }

    public function saveChangesUser(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'department_id' => 'required',
                'role_id' => 'required',
                'is_active' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'validationMessage' => $validator->errors()->toArray(),
                    'message' => 'Please fill all required fields'
                ]);
            }

            $id = $request->id;
            $user = User::find($id);

            if ($user) {
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->department_id = $request->department_id;
                $user->role_id = $request->role_id;
                $user->holiday_id = $request->holiday_id;
                $user->shift_id = $request->shift_id;
                $user->is_active = $request->is_active;
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'User data has been updated'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } else {
            abort(403);
        }
    }

    public function deleteUser(Request $request)
    {
        if (Gate::allows('isManager')) {
            $id = $request->id;
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'User has been deleted'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } else {
            abort(403);
        }
    }

    public function changepassword()
    {
        $navbar = null;
        return view('change-password', compact('navbar'));
    }

    public function saveNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validationMessage' => $validator->errors()->toArray(),
                'message' => 'Please fill all required fields'
            ]);
        }

        $user = Auth::user();
        if (password_verify($request->old_password, $user->password)) {
            if ($request->new_password === $request->confirm_password) {
                $user->password = bcrypt($request->new_password);
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Password has been changed'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'New password and confirm password must be the same'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect'
            ]);
        }
    }
}
