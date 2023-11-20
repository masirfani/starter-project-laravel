<?php

use App\Http\Controllers\Back\AuthController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\back\PermissionController;
use App\Http\Controllers\ExperinmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
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
    return view('welcome');
});
Route::get('/sidebar', function () {
    return view('templates.backend.main-sidebar');
});
Route::get('/navbar', function () {
    return view('templates.backend.main-top-navbar');
});
Route::get('/sidenav', function () {
    return view('templates.backend.main-sidebar-navbar');
});

Route::get('/coba', function () {
    return redirect()->route('auth.login')->with('alert', ["type" => "success", "text" => "Selamat akun anda sudah terdaftar"]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
Route::put('/profile', [AuthController::class, 'update'])->name('auth.update');
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'authentication'])->name('auth.authentication');

Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/register', [AuthController::class, 'store'])->name('auth.store');

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// role management
Route::resource('/role', RoleController::class);
Route::resource('/permission', PermissionController::class);
Route::resource('/experiment', ExperinmentController::class);
