<?php


use App\Http\Controllers\AttendanceHistoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OvertimeHistoryController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\RejectController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect('/presence');
});



Route::get('/login', [AuthController::class, 'showLogin']);
Route::get('/signup', [AuthController::class, 'showSignup']);


Route::get('/presence', [PresenceController::class, 'index']);

Route::get('/request', [RequestController::class, 'index']);

Route::get('/attendance-history', [AttendanceHistoryController::class, 'index']);

Route::get('/overtime-history', [OvertimeHistoryController::class, 'index']);

Route::get('/reject', [RejectController::class, 'index']);

Route::get('/user', [UserController::class, 'index']);
