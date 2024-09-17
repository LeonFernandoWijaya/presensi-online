<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    //
    public function index()
    {
        $navbar = 'request';
        return view('request', compact('navbar'));
    }

    public function saveRequest(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'customer' => 'required',
            'projectName' => 'required',
            'overtimeStart' => 'required|date',
            'overtimeEnd' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request, &$message) {
                    $start = \Carbon\Carbon::parse($request->overtimeStart);
                    $end = \Carbon\Carbon::parse($value);
                    $hours = $end->diffInHours($start);
                    if ($value <= $request->overtimeStart) {
                        $message = $attribute . ' must be after overtime start.';
                        $fail($message);
                    } elseif ($hours > 24) {
                        $message = 'The total overtime must not exceed 24 hours.';
                        $fail($message);
                    }
                },
            ],
            'notes' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validationMessage' => $validator->errors()->toArray(),
                'message' => isset($message) ? $message : 'Please fill all required fields',
            ]);
        }

        $start = \Carbon\Carbon::parse($request->overtimeStart);
        $end = \Carbon\Carbon::parse($request->overtimeEnd);
        $user_id = auth()->user()->id;

        $overlappingOvertime = Overtime::where('user_id', $user_id)
            ->where('overtimeStart', '<', $end)
            ->where('overtimeEnd', '>', $start)
            ->first();

        if ($overlappingOvertime) {
            return response()->json([
                'success' => false,
                'message' => 'The new overtime period overlaps with an existing one.',
            ]);
        }

        $overtime = new Overtime();
        $overtime->user_id = auth()->user()->id;
        $overtime->overtimeStart = $request->overtimeStart;
        $overtime->overtimeEnd = $request->overtimeEnd;
        $overtime->overtimeTotal = \Carbon\Carbon::parse($request->overtimeStart)->diffInMinutes(\Carbon\Carbon::parse($request->overtimeEnd));
        $overtime->notes = $request->notes;
        $overtime->customer = $request->customer;
        $overtime->projectName = $request->projectName;
        $overtime->save();
        return response()->json(['success' => true, 'message' => 'Request created successfully']);
    }
}
