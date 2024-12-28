<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Menampilkan form registrasi
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::get('register/penyewa', [AuthController::class, 'showRegistrationFormPenyewa'])->name('register.penyewa');
Route::get('register/pemilik', [AuthController::class, 'showRegistrationFormPemilik'])->name('register.pemilik');


// Menangani proses registrasi
Route::post('register', [AuthController::class, 'register'])->name('register');

// Menampilkan form verifikasi OTP
Route::get('verify-otp/{id}', [AuthController::class, 'showVerificationForm'])->name('verifyOtp.form');

// Menangani proses verifikasi OTP
Route::post('verify-otp/{id}', [AuthController::class, 'verifyOtp'])->name('verifyOtp');

// Menampilkan form login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');

// Menangani proses login
Route::post('login', [AuthController::class, 'login'])->name('login');

// Menangani proses logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('resend-otp/{id}', [AuthController::class, 'resendOtp'])->name('resendOtp');

// Dashboard setelah login berhasil
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');
Route::get('/', function () {
    return view('index');
});

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('ayongbhara01@gmail.com')->subject('Test Email');
    });

    return 'Email sent!';
});
