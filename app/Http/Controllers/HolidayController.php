<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\HolidayDay;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index()
    {
        if (Gate::allows('isManager')) {
            $navbar = 'holiday';
            return view('holiday', compact('navbar'));
        } else {
            abort(403);
        }
    }

    public function getAllHolidayCategory(Request $request)
    {
        if (Gate::allows('isManager')) {
            $holiday = Holiday::where('holiday_name', 'like', '%' . $request->search . '%')
                ->paginate(5);
            return response()->json($holiday);
        } else {
            abort(403);
        }
    }

    public function createNewHolidayCategory(Request $request)
    {
        if (Gate::allows('isManager')) {
            $validator = Validator::make($request->all(), [
                'holiday_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ]);
            }

            $holiday = new Holiday();
            $holiday->holiday_name = $request->holiday_name;
            $holiday->save();

            return response()->json(['success' => true, 'message' => 'Holiday created successfully']);
        } else {
            abort(403);
        }
    }

    public function deleteHolidayCategory(Request $request)
    {
        if (Gate::allows('isManager')) {
            $holiday = Holiday::find($request->id);
            $holiday->delete();
            return response()->json(['success' => true, 'message' => 'Holiday deleted successfully']);
        } else {
            abort(403);
        }
    }

    public function holidayDays($id)
    {
        if (Gate::allows('isManager')) {
            $navbar = 'holiday';
            $holiday = Holiday::find($id);
            $years = $holiday->holidayDays()->selectRaw('YEAR(holiday_date) as year')
                ->groupBy('year')
                ->pluck('year')
                ->toArray();
            return view('holiday-days', compact('navbar', 'years', 'holiday'));
        } else {
            abort(403);
        }
    }

    public function saveNewHoliday(Request $request)
    {
        if (Gate::allows('isManager')) {
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
        } else {
            abort(403);
        }
    }

    public function getHolidays(Request $request)
    {
        if (Gate::allows('isManager')) {
            $holidays = HolidayDay::where('holiday_id', )
            return response()->json($holidays);
        } else {
            abort(403);
        }
    }

    public function deleteHoliday(Request $request)
    {
        if (Gate::allows('isManager')) {
            $holiday = Holiday::find($request->id);
            $holiday->delete();
            return response()->json(['success' => true, 'message' => 'Holiday deleted successfully']);
        } else {
            abort(403);
        }
    }

    public function getNationalDayOffAPI(Request $request)
    {
        if (Gate::allows('isManager')) {
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
        } else {
            abort(403);
        }
    }

    public function saveAllImportNationalDayOff(Request $request)
    {
        if (Gate::allows('isManager')) {
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
        } else {
            abort(403);
        }
    }

    public function getHolidayById(Request $request)
    {
        if (Gate::allows('isManager')) {
            $holiday = Holiday::find($request->id);
            return response()->json($holiday);
        } else {
            abort(403);
        }
    }

    public function saveChangesHoliday(Request $request)
    {
        if (Gate::allows('isManager')) {
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
        } else {
            abort(403);
        }
    }
}
