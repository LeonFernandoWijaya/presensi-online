<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Overtime;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PresenceController extends Controller
{
    //
    public function index()
    {
        $navbar = 'presence';
        $lastPresence = Auth::user()->presences->last();
        $isClockOut = false;

        if ($lastPresence && $lastPresence->clockOutTime == null && $lastPresence->clockInTime != null) {
            $isClockOut = true;
        }
        return view('presence', compact('navbar', 'isClockOut'));
    }

    public function checkSchedule()
    {
        $shiftDays = Auth::user()->shift->shiftDays;
        return response()->json($shiftDays);
    }

    public function presenceNow(Request $request)
    {
        $isOvertime = $request->isOvertime == 'true' ? 1 : 0;
        $statusPresence = null;
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
            'sendLatitude' => 'required',
            'sendLongitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'validationMessage' => $validator->errors()->toArray(),
                'message' => 'Make sure you already take photo and allow location access',
            ]);
        }

        $photo = $request->photo;

        // Remove the base64 prefix
        list($type, $photo) = explode(';', $photo);
        list(, $photo) = explode(',', $photo);

        // Decode the base64 data
        $photo = base64_decode($photo);

        // Generate a unique filename
        $filename = uniqid() . '.png';

        // Store the image in the 'public/photos' directory
        Storage::put('public/photos/' . $filename, $photo);

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                'Referer' => 'http://127.0.0.1:8000/'
            ]
        ]);

        try {
            $response = $client->request(
                'GET',
                'https://nominatim.openstreetmap.org/reverse',
                [
                    'query' => [
                        'format' => 'json',
                        'lat' => $request->sendLatitude,
                        'lon' => $request->sendLongitude,
                    ]
                ]
            );
        } catch (\Throwable $th) {
            dd($th->getResponse()->getBody()->getContents());
        }

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        $locationName = $data['display_name'];

        $lastPresence = Attendance::where('user_id', Auth::user()->id)->latest()->first();
        if ($lastPresence && $lastPresence->clockOutTime == null && $lastPresence->clockInTime != null) {
            $lastPresence->clockOutTime = date('Y-m-d H:i:s');
            $lastPresence->clockOutPhoto = $filename;
            $lastPresence->clockOutLocation = $locationName;
            $lastPresence->isOvertimeClockOut = $isOvertime;
            $lastPresence->save();

            $statusPresence = 'clockOut';

            // check for overtime
            $totalOvertime = 0;
            $isHoliday = Holiday::where('holiday_date', date('Y-m-d'))->first();
            $isShiftDay = Auth::user()->shift->shiftDays->where('dayName', date('l', strtotime($lastPresence->clockInTime)))->first();

            $clockInTime = \Carbon\Carbon::parse($lastPresence->clockInTime);
            $clockOutTime = \Carbon\Carbon::parse($lastPresence->clockOutTime);
    
            if($isShiftDay){
                $dateTime = new DateTime($lastPresence->clockInTime);
                $today = $dateTime->format('Y-m-d');
                $shiftStart = $today . ' ' . $isShiftDay->startHour;
                $shiftEnd = $today . ' ' . $isShiftDay->endHour;   
                $shiftStartTime = \Carbon\Carbon::parse($shiftStart);
                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);
            }
            
            if($isHoliday){
                $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                $this->makeOvertime($lastPresence->id, $clockInTime, $clockOutTime, $totalOvertime);
            } else {
                if ($lastPresence->isOvertimeClockIn == 1 || $lastPresence->isOvertimeClockOut == 1) {
                    if ($isShiftDay != null) { 
                        if ($clockInTime > $shiftEndTime && $lastPresence->isOvertimeClockIn == 1) {
                            $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                        } else {
                            if ($clockInTime < $shiftStartTime && $clockOutTime < $shiftStartTime){
                                $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                            } else if ($clockInTime < $shiftStartTime || $clockOutTime > $shiftEndTime) {
                                // If clocked in early and it's considered overtime
                                if ($clockInTime < $shiftStartTime && $lastPresence->isOvertimeClockIn == 1){
                                    $totalOvertime = $totalOvertime + $shiftStartTime->diffInMinutes($clockInTime);
                                }

                                // If clocked out late and it's considered overtime
                                if($clockOutTime > $shiftEndTime && $lastPresence->isOvertimeClockOut == 1){
                                    $totalOvertime = $totalOvertime + $clockOutTime->diffInMinutes($shiftEndTime);
                                }
                            }
                        }
                        $this->makeOvertime($lastPresence->id, $clockInTime, $clockOutTime, $totalOvertime);
                    } else if ($isShiftDay == null  && $lastPresence->isOvertimeClockIn == 1) {
                        $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                        $this->makeOvertime($lastPresence->id, $clockInTime, $clockOutTime, $totalOvertime);
                    }
                }   
            }
        } else {
            $attendance = new Attendance();
            $attendance->user_id = Auth::user()->id;
            $attendance->clockInTime = date('Y-m-d H:i:s');
            $attendance->clockInPhoto = $filename;
            $attendance->clockInLocation = $locationName;
            $attendance->isOvertimeClockIn = $isOvertime;
            $attendance->save();

            $statusPresence = 'clockIn';
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Presence recorded successfully',
            'statusPresence' => $statusPresence,
        ]);
    }

    private function makeOvertime($attendanceId, $overtimeStart, $overtimeEnd, $overtimeTotal)
    {
        $overtime = new Overtime();
        $overtime->user_id = Auth::user()->id;
        $overtime->attendance_id = $attendanceId;
        $overtime->overtimeStart = $overtimeStart;
        $overtime->overtimeEnd = $overtimeEnd;
        $overtime->overtimeTotal = $overtimeTotal;
        $overtime->save();
    }
}

