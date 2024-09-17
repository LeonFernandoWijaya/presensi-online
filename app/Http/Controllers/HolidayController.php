<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index()
    {
        $navbar = 'holiday';
        $years = Holiday::selectRaw('YEAR(holiday_date) as year')->distinct()->pluck('year')->toArray();
        return view('holiday', compact('navbar', 'years'));
    }

    public function saveNewHoliday(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'holidayName' => 'required',
            'holidayDate' => 'required|unique:holidays,holiday_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $holiday = new Holiday();
        $holiday->holiday_date = $request->holidayDate;
        $holiday->holiday_name = $request->holidayName;
        $holiday->save();

        return response()->json(['success' => true, 'message' => 'Holiday created successfully']);
    }
}
