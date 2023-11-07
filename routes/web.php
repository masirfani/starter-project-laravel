<?php

use App\Http\Controllers\Back\DashboardController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
