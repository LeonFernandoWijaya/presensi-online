<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

use function Symfony\Component\VarDumper\Dumper\esc;

class ShiftController extends Controller
{
    //

    public function index()
    {
        if (Gate::allows('isManager')) {
            $navbar = 'shift';
            return view('shift', compact('navbar'));
        } else {
            abort(403);
        }
    }

    public function createNewShift(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'shiftName' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'validationMessage' => $validator->errors()->toArray(),
                    'message' => 'Please fill all required fields'
                ]);
            }

            $shift = new Shift();
            $shift->shift_name = $request->shiftName;
            $shift->save();
            return response()->json(['success' => true, 'message' => 'Shift created successfully']);
        } else {
            abort(403);
        }
    }

    public function getShifts(Request $request)
    {
        if (Gate::allows('isManager')) {
            $shiftSearch = $request->shiftSearch;
            $shifts = Shift::where('shift_name', 'like', '%' . $shiftSearch . '%')->paginate(10);
            return response()->json($shifts);
        } else {
            abort(403);
        }
    }

    public function deleteShift(Request $request)
    {
        if (Gate::allows('isManager')) {
            $shift = Shift::find($request->id);
            $shift->delete();
            return response()->json(['success' => true, 'message' => 'Shift deleted successfully']);
        } else {
            abort(403);
        }
    }

    public function getShiftById(Request $request)
    {
        if (Gate::allows('isManager')) {
            $shift = Shift::with('shiftDays')->find($request->id);

            return response()->json($shift);
        } else {
            abort(403);
        }
    }

    public function addShiftDay(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'dayName' => 'required',
                'startHour' => 'required',
                'endHour' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'validationMessage' => $validator->errors()->toArray(),
                    'message' => 'Please fill all required fields'
                ]);
            }

            $shift = Shift::find($request->shiftId);
            $shift->shiftDays()->create([
                'dayName' => $request->dayName,
                'startHour' => $request->startHour,
                'endHour' => $request->endHour
            ]);

            return response()->json(['success' => true, 'message' => 'Shift day added successfully']);
        } else {
            abort(403);
        }
    }

    public function deleteShiftDay(Request $request)
    {
        if (Gate::allows('isManager')) {
            $shiftDay = ShiftDay::find($request->id);
            $shiftId = $shiftDay->shift_id;
            $shiftDay->delete();
            return response()->json(['success' => true, 'message' => 'Shift day deleted successfully', 'id' => $shiftId]);
        } else {
            abort(403);
        }
    }

    function updateShift(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'shiftName' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'validationMessage' => $validator->errors()->toArray(),
                    'message' => 'Please fill all required fields'
                ]);
            }

            $shift = Shift::find($request->shiftId);
            $shift->shift_name = $request->shiftName;
            $shift->save();
            return response()->json(['success' => true, 'message' => 'Shift updated successfully']);
        } else {
            abort(403);
        }
    }


    function getShiftDayById(Request $request)
    {
        if (Gate::allows('isManager')) {
            $shiftDay = ShiftDay::find($request->id);
            return response()->json($shiftDay);
        } else {
            abort(403);
        }
    }

    function updateShiftDay(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'dayName' => 'required',
                'startHour' => 'required',
                'endHour' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'validationMessage' => $validator->errors()->toArray(),
                    'message' => 'Please fill all required fields'
                ]);
            }

            $shiftDay = ShiftDay::find($request->id);
            $shiftDay->dayName = $request->dayName;
            $shiftDay->startHour = $request->startHour;
            $shiftDay->endHour = $request->endHour;
            $shiftDay->save();
            return response()->json(['success' => true, 'message' => 'Shift day updated successfully', 'id' => $shiftDay->shift_id]);
        } else {
            abort(403);
        }
    }
}
