<?php


use App\Http\Controllers\AttendanceHistoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OvertimeHistoryController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ShiftController;
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
});

Route::post('/logout', [AuthController::class, 'logout']);



Route::middleware(['is_active', 'auth'])->group(function () {
    Route::get('/presence', [PresenceController::class, 'index']);
    Route::get('/request', [RequestController::class, 'index']);
    Route::get('/attendance-history', [AttendanceHistoryController::class, 'index']);
    Route::get('/overtime-history', [OvertimeHistoryController::class, 'index']);
    Route::get('/reject', [RejectController::class, 'index']);
    Route::get('/user', [UserController::class, 'index']);

    Route::get('shift', [ShiftController::class, 'index']);


    Route::get('/getInitialDataModal', [UserController::class, 'getInitialDataModal']);

    Route::get('/getAllUsers', [UserController::class, 'getAllUsers']);
    Route::get('/getUserById', [UserController::class, 'getUserById']);
    Route::put('/saveChangesUser', [UserController::class, 'saveChangesUser']);
    Route::delete('/deleteUser', [UserController::class, 'deleteUser']);

    Route::post('/createNewShift', [ShiftController::class, 'createNewShift']);
    Route::get('/getShifts', [ShiftController::class, 'getShifts']);
    Route::delete('/deleteShift', [ShiftController::class, 'deleteShift']);
    Route::get('/getShiftById', [ShiftController::class, 'getShiftById']);
    Route::put('/updateShift', [ShiftController::class, 'updateShift']);

    Route::post('/addShiftDay', [ShiftController::class, 'addShiftDay']);
    Route::delete('/deleteShiftDay', [ShiftController::class, 'deleteShiftDay']);
});
