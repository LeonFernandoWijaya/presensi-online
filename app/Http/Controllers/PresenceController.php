<?php

namespace App\Http\Controllers;

use App\Models\ActivityCategory;
use App\Models\ActivityType;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\HolidayDay;
use App\Models\Overtime;
use App\Models\ShiftScheduling;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;

class PresenceController extends Controller
{
    //
    public function index()
    {
        $navbar = 'presence';
        $lastPresence = Auth::user()->presences->last();
        $isClockOut = false;
        $activityTypes = ActivityType::all();
        $activityCategories = ActivityCategory::all();

        if ($lastPresence && $lastPresence->clockOutTime == null && $lastPresence->clockInTime != null) {
            $isClockOut = true;
        }
        return view('presence', compact('navbar', 'isClockOut', 'activityTypes', 'activityCategories'));
    }

    public function checkStatusPresence()
    {
        $lastPresence = Auth::user()->presences->last();
        $isClockOut = false;
        $PresenceData = null;

        if ($lastPresence && $lastPresence->clockOutTime == null && $lastPresence->clockInTime != null) {
            $isClockOut = true;
            $PresenceData = $lastPresence;
        }

        return response()->json(['isClockOut' => $isClockOut, 'PresenceData' => $PresenceData]);
    }

    public function getCurrentTime()
    {
        $currentTime = Carbon::now()->toDateTimeString();
        return response()->json($currentTime);
    }

    public function checkSchedule()
    {
        $shiftDays = Auth::user()->shift->shiftDays;

        // Define the order of days from Monday to Sunday
        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Convert the collection to an array
        $shiftDaysArray = $shiftDays->toArray();

        // Sort the shiftDays based on the defined order and startHour
        usort($shiftDaysArray, function ($a, $b) use ($dayOrder) {
            $posA = array_search($a['dayName'], $dayOrder);
            $posB = array_search($b['dayName'], $dayOrder);

            if ($posA === $posB) {
                return strcmp($a['startHour'], $b['startHour']);
            }

            return $posA - $posB;
        });

        // Convert the array back to a collection
        $sortedShiftDays = collect($shiftDaysArray);

        return response()->json($sortedShiftDays);
    }

    public function presenceNow(Request $request)
    {
        $statusPresence = null;
        $validator = Validator::make($request->all(), [
            'photo' => 'required',
            'sendLatitude' => 'required',
            'sendLongitude' => 'required',
            'activityTypes' => 'required',
            'activityCategories' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'validationMessage' => $validator->errors()->toArray(),
                'message' => 'Make sure you already take photo, allow location access, and fill all required fields',
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
                    ],
                    'timeout' => 10
                ]
            );
        } catch (ConnectException $e) {
            // Jika terjadi timeout, buat permintaan ke API alternatif
            $response = $client->request(
                'GET',
                'https://us1.locationiq.com/v1/reverse',
                [
                    'query' => [
                        'key' => 'pk.460d675996d878661445851022dd0fc9',
                        'lat' => $request->sendLatitude,
                        'lon' => $request->sendLongitude,
                        'format' => 'json',
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

        try {
            $clientTimeNow = new Client();
            $responseTimeNow = $clientTimeNow->request('GET', 'https://timeapi.io/api/time/current/zone?timeZone=Asia%2FJakarta');
            $dataTimeNow = json_decode($responseTimeNow->getBody(), true);
            $timeNow = $dataTimeNow['dateTime'];
            $dateTimeNow = new \DateTime($timeNow);
        } catch (RequestException $e) {
            // Handle the exception (e.g., log the error, return a default value, etc.)
            error_log('HTTP Request failed: ' . $e->getMessage());
            $clientTimeNow = new Client();
            $responseTimeNow = $clientTimeNow->request('GET', 'http://worldtimeapi.org/api/Asia/Jakarta');
            $dataTimeNow = json_decode($responseTimeNow->getBody(), true);
            $timeNow = $dataTimeNow['datetime'];
            $dateTimeNow = new \DateTime($timeNow);
        }

        if ($lastPresence && $lastPresence->clockOutTime == null && $lastPresence->clockInTime != null) {
            $lastPresence->clockOutTime = $dateTimeNow->format('Y-m-d H:i:s');
            $lastPresence->clockOutPhoto = $filename;
            $lastPresence->clockOutLocation = $locationName;
            $lastPresence->activity_type_id = $request->activityTypes;
            $lastPresence->activity_category_id = $request->activityCategories;
            $lastPresence->customer = $request->customerName;
            $lastPresence->isClockOutAtOffice = $this->isRemote($request->sendLongitude, $request->sendLatitude);
            // $lastPresence->save();

            $statusPresence = 'clockOut';

            // check for overtime
            $totalOvertime = 0;
            $findHolidayId = Auth::user()->holiday->id;
            $getTodayDateForCheck = Carbon::parse($lastPresence->clockInTime)->format('Y-m-d');
            $isHoliday = HolidayDay::where('holiday_id', $findHolidayId)->where('holiday_date', $getTodayDateForCheck)->first();
            // $isShiftDay = Auth::user()->shift->shiftDays->where('dayName', date('l', strtotime($lastPresence->clockInTime)))->first();
            $findSchedule = ShiftScheduling::where('user_id', Auth::user()->id)
                ->where('start_date', '<=', $getTodayDateForCheck)
                ->where('end_date', '>=', $getTodayDateForCheck)
                ->first();
            if($findSchedule){
                $isShiftDay = $findSchedule->shift->shiftDays->where('dayName', date('l', strtotime($lastPresence->clockInTime)))->first();
            }else{
                $isShiftDay = null;
            }
            
            dd($isShiftDay);

            $clockInTime = \Carbon\Carbon::parse($lastPresence->clockInTime);
            $clockOutTime = \Carbon\Carbon::parse($lastPresence->clockOutTime);

            if ($isShiftDay) {
                $dateTime = new DateTime($lastPresence->clockInTime);
                $today = $dateTime->format('Y-m-d');
                $shiftStart = $today . ' ' . $isShiftDay->startHour;
                $shiftEnd = $today . ' ' . $isShiftDay->endHour;
                $shiftStartTime = \Carbon\Carbon::parse($shiftStart);
                $shiftEndTime = \Carbon\Carbon::parse($shiftEnd);
            }

            if ($isHoliday) {
                $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                $this->makeOvertime($lastPresence->id, $clockInTime, $clockOutTime, $totalOvertime);
            } else {
                if ($isShiftDay != null) {
                    if ($clockInTime > $shiftEndTime) {
                        $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                    } else {
                        if ($clockInTime < $shiftStartTime && $clockOutTime <= $shiftStartTime) {
                            $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                        } else if ($clockInTime < $shiftStartTime || $clockOutTime > $shiftEndTime) {
                            // If clocked in early and it's considered overtime
                            if ($clockInTime < $shiftStartTime) {
                                $totalOvertime = $totalOvertime + $shiftStartTime->diffInMinutes($clockInTime);
                            }

                            // If clocked out late and it's considered overtime
                            if ($clockOutTime > $shiftEndTime) {
                                $totalOvertime = $totalOvertime + $clockOutTime->diffInMinutes($shiftEndTime);
                            }
                        }
                    }
                    $this->makeOvertime($lastPresence->id, $clockInTime, $clockOutTime, $totalOvertime);
                } else if ($isShiftDay == null) {
                    $totalOvertime = $clockInTime->diffInMinutes($clockOutTime);
                    $this->makeOvertime($lastPresence->id, $clockInTime, $clockOutTime, $totalOvertime);
                }
            }
        } else {
            $attendance = new Attendance();
            $attendance->user_id = Auth::user()->id;
            $attendance->clockInTime = $dateTimeNow->format('Y-m-d H:i:s');
            $attendance->clockInPhoto = $filename;
            $attendance->clockInLocation = $locationName;
            $attendance->activity_type_id = $request->activityTypes;
            $attendance->activity_category_id = $request->activityCategories;
            $attendance->customer = $request->customerName;
            $attendance->isClockInAtOffice = $this->isRemote($request->sendLongitude, $request->sendLatitude);
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
        if ($overtimeTotal > 0) {
            $overtime = new Overtime();
            $overtime->user_id = Auth::user()->id;
            $overtime->attendance_id = $attendanceId;
            $overtime->overtimeStart = $overtimeStart;
            $overtime->overtimeEnd = $overtimeEnd;
            $overtime->overtimeTotal = $overtimeTotal;
            $overtime->save();
        }
    }

    private function isRemote($userLongitude, $userLatitude)
    {
        // // haversine formula
        // $userLatitude = -6.17019594842749;
        // $userLongitude = 106.831384975091;

        $officeLongitude = 106.797794951068; // Ganti dengan longitude kantor
        $officeLatitude = -6.19058187063816; // Ganti dengan latitude kantor,

        $earthRadius = 6371; // Radius bumi dalam kilometer

        $dLatitude = deg2rad($officeLatitude - $userLatitude);
        $dLongitude = deg2rad($officeLongitude - $userLongitude);

        $a = sin($dLatitude / 2) * sin($dLatitude / 2) +
            cos(deg2rad($userLatitude)) * cos(deg2rad($officeLatitude)) *
            sin($dLongitude / 2) * sin($dLongitude / 2);
        $c = 2 * asin(min(1, sqrt($a)));
        $distance = $earthRadius * $c;

        // dd($distance, $userLongitude, $userLatitude);
        if ($distance <= 0.02) {
            return 1;
        } else {
            return 0;
        }
    }
}
