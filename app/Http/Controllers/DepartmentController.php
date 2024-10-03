<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    //
    public function index()
    {
        if (Gate::allows('isManager')) {
            $navbar = 'settings';
            return view('department', compact('navbar'));
        } else {
            abort(403);
        }
    }

    public function createDepartment(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'department_name' => 'required|string|max:60'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $department = new Department();
            $department->department_name = $request->department_name;
            $department->save();
            return response()->json([
                'success' => true,
                'message' => 'Department created successfully'
            ]);
        } else {
            abort(403);
        }
    }

    public function getDepartments(Request $request)
    {
        if (Gate::allows('isManager')) {
            $departments = Department::where('department_name', 'like', '%' . $request->search . '%')->paginate(5);
            return response()->json($departments);
        } else {
            abort(403);
        }
    }

    public function getDepartmentDetail(Request $request){
        if (Gate::allows('isManager')) {
            $department = Department::find($request->id);
            return response()->json($department);
        } else {
            abort(403);
        }
    }

    public function updateDepartment(Request $request){
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'department_name' => 'required|string|max:60'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $department = Department::find($request->id);
            $department->department_name = $request->department_name;
            $department->save();
            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully'
            ]);
        } else {
            abort(403);
        }
    }

    public function deleteDepartment(Request $request){
        if (Gate::allows('isManager')) {
            $department = Department::find($request->id);
            $department->delete();
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } else {
            abort(403);
        }
    }
}
