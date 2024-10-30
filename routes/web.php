<?php


use App\Http\Controllers\AttendanceHistoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\OvertimeHistoryController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftSchedulingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth::routes(['verify' => true]);

Route::get('/', function () {
    return redirect('/presence');
});

Route::get('/error-page', function () {
    return view('error-page', ['navbar' => null]);
});


Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/signUp', [AuthController::class, 'signUp']);

    Route::post('/store-sign-up', [AuthController::class, 'storeSignUp']);
    Route::post('/store-login', [AuthController::class, 'storeLogin']);
    Route::put('/verify-email', [AuthController::class, 'verifyEmail']);
});

Route::post('/logout', [AuthController::class, 'logout']);



Route::middleware(['is_active', 'auth', 'check_session'])->group(function () {
    Route::get('/presence', [PresenceController::class, 'index']);
    Route::get('/request', [RequestController::class, 'index']);
    Route::get('/attendance-history', [AttendanceHistoryController::class, 'index']);
    Route::get('/overtime-history', [OvertimeHistoryController::class, 'index']);
    Route::get('/reject', [RejectController::class, 'index']);
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/department', [DepartmentController::class, 'index']);
    Route::get('/holiday', [HolidayController::class, 'index']);
    Route::get('/holiday-days/{id}', [HolidayController::class, 'holidayDays']);
    Route::get('/change-password', [UserController::class, 'changePassword']);
    Route::put('/saveNewPassword', [UserController::class, 'saveNewPassword']);
    Route::get('/shift-scheduling', [ShiftSchedulingController::class, 'index']);

    Route::get('shift', [ShiftController::class, 'index']);

    Route::get('/getCurrentTime', [PresenceController::class, 'getCurrentTime']);
    Route::get('/checkStatusPresence', [PresenceController::class, 'checkStatusPresence']);

    Route::get('/getInitialDataModal', [UserController::class, 'getInitialDataModal']);

    Route::get('/getAllUsers', [UserController::class, 'getAllUsers']);
    Route::get('/getUserById', [UserController::class, 'getUserById']);
    Route::put('/saveChangesUser', [UserController::class, 'saveChangesUser']);
    Route::delete('/deleteUser', [UserController::class, 'deleteUser']);

    Route::get('/getDepartments', [DepartmentController::class, 'getDepartments']);
    Route::post('/createDepartment', [DepartmentController::class, 'createDepartment']);
    Route::get('/getDepartmentDetail', [DepartmentController::class, 'getDepartmentDetail']);
    Route::put('/updateDepartment', [DepartmentController::class, 'updateDepartment']);
    Route::delete('/deleteDepartment', [DepartmentController::class, 'deleteDepartment']);

    Route::post('/createNewShift', [ShiftController::class, 'createNewShift']);
    Route::get('/getShifts', [ShiftController::class, 'getShifts']);
    Route::delete('/deleteShift', [ShiftController::class, 'deleteShift']);
    Route::get('/getShiftById', [ShiftController::class, 'getShiftById']);
    Route::put('/updateShift', [ShiftController::class, 'updateShift']);

    Route::post('/addShiftDay', [ShiftController::class, 'addShiftDay']);
    Route::delete('/deleteShiftDay', [ShiftController::class, 'deleteShiftDay']);
    Route::get('/getShiftDayById', [ShiftController::class, 'getShiftDayById']);
    Route::put('/updateShiftDay', [ShiftController::class, 'updateShiftDay']);

    Route::get('/getAllHolidayCategory', [HolidayController::class, 'getAllHolidayCategory']);
    Route::post('/createNewHolidayCategory', [HolidayController::class, 'createNewHolidayCategory']);
    Route::delete('/deleteHolidayCategory', [HolidayController::class, 'deleteHolidayCategory']);

    Route::post('/saveNewHoliday', [HolidayController::class, 'saveNewHoliday']);
    Route::get('/getHolidayById', [HolidayController::class, 'getHolidayById']);
    Route::get('/getHolidays', [HolidayController::class, 'getHolidays']);
    Route::put('/saveChangesHoliday', [HolidayController::class, 'saveChangesHoliday']);
    Route::delete('/deleteHoliday', [HolidayController::class, 'deleteHoliday']);
    Route::get('/getNationalDayOffAPI', [HolidayController::class, 'getNationalDayOffAPI']);
    Route::post('/saveAllImportNationalDayOff', [HolidayController::class, 'saveAllImportNationalDayOff']);

    Route::post('/saveRequest', [RequestController::class, 'saveRequest']);

    Route::get('/getOvertimeForReject', [RejectController::class, 'getOvertimeForReject']);
    Route::put('/rejectOvertime', [RejectController::class, 'rejectOvertime']);
    Route::put('/rejectSelectedOvertime', [RejectController::class, 'rejectSelectedOvertime']);
    Route::get('/downloadReject', [RejectController::class, 'downloadReject']);

    Route::get('/checkSchedule', [PresenceController::class, 'checkSchedule']);
    Route::post('/presenceNow', [PresenceController::class, 'presenceNow']);


    Route::get('/getAttendanceHistory', [AttendanceHistoryController::class, 'getAttendanceHistory']);
    Route::get('/getAttendanceDetail', [AttendanceHistoryController::class, 'getAttendanceDetail']);
    Route::get('/getOvertimeHistory', [OvertimeHistoryController::class, 'getOvertimeHistory']);
    Route::get('/getOvertimeDetail', [OvertimeHistoryController::class, 'getOvertimeDetail']);

    Route::get('/downloadOvertimeHistory', [OvertimeHistoryController::class, 'downloadOvertimeHistory']);
    Route::get('/downloadAttendanceHistory', [AttendanceHistoryController::class, 'downloadAttendanceHistory']);

    Route::post('/saveNewSchedule', [ShiftSchedulingController::class, 'saveNewSchedule']);
    Route::get('/getShiftSchedule', [ShiftSchedulingController::class, 'getShiftSchedule']);
    Route::get('/getShiftScheduleDetail', [ShiftSchedulingController::class, 'getShiftScheduleDetail']);
    Route::put('/updateSchedule', [ShiftSchedulingController::class, 'updateSchedule']);
    Route::delete('/deleteSchedule', [ShiftSchedulingController::class, 'deleteSchedule']);
    Route::post('/previewImport', [ShiftSchedulingController::class, 'previewImport']);
    Route::post('/importNow', [ShiftSchedulingController::class, 'importNow']);
});
