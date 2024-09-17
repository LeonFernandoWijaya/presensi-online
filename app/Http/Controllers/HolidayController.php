<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use GuzzleHttp\Client;
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

    public function getHolidays(Request $request)
    {
        $holidays = Holiday::whereYear('holiday_date', $request->year)
            ->orderBy('holiday_date', 'asc')
            ->paginate(10);
        return response()->json($holidays);
    }

    public function deleteHoliday(Request $request)
    {
        $holiday = Holiday::find($request->id);
        $holiday->delete();
        return response()->json(['success' => true, 'message' => 'Holiday deleted successfully']);
    }

    public function getNationalDayOffAPI(Request $request)
    {
        $client = new Client();
        $year = $request->year;
        $response = $client->request('GET', "https://dayoffapi.vercel.app/api?year={$year}");

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $data = json_decode($body, true);
            return $data;
        } else {
            return null;
        }
    }

    public function saveAllImportNationalDayOff(Request $request)
    {
        $existingDates = [];
        $totalSuccess = 0;
        $totalNotStored = 0;
        $holidays = $request->nationalDayOffArray;

        foreach ($holidays as $holiday) {
            // Check if the holiday date already exists in the database
            $existingHoliday = Holiday::where('holiday_date', $holiday['tanggal'])->first();

            if ($existingHoliday) {
                // If the holiday date exists, push it to the array
                $existingDates[] = $holiday['tanggal'] . ' => ' . $holiday['keterangan'];
                $totalNotStored++;
            } else {
                // If the holiday date does not exist, create a new record
                $holidayModel = new Holiday();
                $holidayModel->holiday_date = $holiday['tanggal'];
                $holidayModel->holiday_name = $holiday['keterangan'];
                $holidayModel->save();
                $totalSuccess++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Holidays imported successfully',
            'existingDates' => $existingDates,
            'totalSuccess' => $totalSuccess,
            'totalNotStored' => $totalNotStored
        ]);
    }

    public function getHolidayById(Request $request)
    {
        $holiday = Holiday::find($request->id);
        return response()->json($holiday);
    }

    public function saveChangesHoliday(Request $request)
    {
        $request->only('id', 'holidayName');
        $validator = Validator::make($request->all(), [
            'holidayName' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $holiday = Holiday::find($request->id);
        $holiday->holiday_name = $request->holidayName;
        $holiday->save();

        return response()->json(['success' => true, 'message' => 'Holiday updated successfully']);
    }
}
