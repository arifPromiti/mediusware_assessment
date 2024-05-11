<?php

use App\Http\Controllers\DashboardController;
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

Route::get('/', function(){return view('auth.login');});

Route::get('/user/create/', function(){return view('auth.login');});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/deposit/form', [DashboardController::class, 'deposit'])->name('deposit');
    Route::post('/deposit/add', [DashboardController::class, 'depositAdd'])->name('deposit.add');
    Route::get('/withdrawal/form', [DashboardController::class, 'withdrawal'])->name('withdrawal');
    Route::post('/withdraw/add', [DashboardController::class, 'withdrawAdd'])->name('withdraw.add');
});

require __DIR__.'/auth.php';
