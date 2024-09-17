<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    //

    public function index()
    {
        $navbar = 'shift';
        return view('shift', compact('navbar'));
    }

    public function createNewShift(Request $request)
    {
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
    }

    public function getShifts(Request $request)
    {
        $shiftSearch = $request->shiftSearch;
        $shifts = Shift::where('shift_name', 'like', '%' . $shiftSearch . '%')->paginate(10);
        return response()->json($shifts);
    }

    public function deleteShift(Request $request)
    {
        $shift = Shift::find($request->id);
        $shift->delete();
        return response()->json(['success' => true, 'message' => 'Shift deleted successfully']);
    }

    public function getShiftById(Request $request)
    {
        $shift = Shift::with('shiftDays')->find($request->id);

        return response()->json($shift);
    }

    public function addShiftDay(Request $request)
    {
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
    }

    public function deleteShiftDay(Request $request)
    {
        $shiftDay = ShiftDay::find($request->id);
        $shiftId = $shiftDay->shift_id;
        $shiftDay->delete();
        return response()->json(['success' => true, 'message' => 'Shift day deleted successfully', 'id' => $shiftId]);
    }

    function updateShift(Request $request)
    {
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
    }


    function getShiftDayById(Request $request)
    {
        $shiftDay = ShiftDay::find($request->id);
        return response()->json($shiftDay);
    }

    function updateShiftDay(Request $request)
    {
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
    }
}
