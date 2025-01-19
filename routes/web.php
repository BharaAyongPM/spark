<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FieldTypeController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\VendorController;
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

//Google
Route::get('login/google/{role}', [SocialController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [SocialController::class, 'handleGoogleCallback']);
//FACEBOOK
Route::get('login/facebook', [SocialController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [SocialController::class, 'handleFacebookCallback']);


//ADMIN
Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('is_admin');
Route::get('admin/users', [AdminController::class, 'viewUsers'])->name('admin.users');
Route::get('admin/fields', [AdminController::class, 'viewFields'])->name('admin.fields');
Route::get('admin/orders', [AdminController::class, 'viewOrders'])->name('admin.orders');
Route::resource('admin/facilities', FacilityController::class)->except(['show', 'create', 'edit']);
Route::resource('admin/field_types', FieldTypeController::class)->except(['show', 'create', 'edit']);

//VENDOR
Route::middleware(['auth', 'role:PEMILIK'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');

    Route::get('/vendor/fields', [VendorController::class, 'fieldsIndex'])->name('vendor.fields.index');
    Route::post('/vendor/fields', [VendorController::class, 'fieldsStore'])->name('vendor.fields.store');
    Route::delete('/vendor/fields/{field}', [VendorController::class, 'fieldsDestroy'])->name('vendor.fields.destroy');
    Route::get('/vendor/fields/{field}', [VendorController::class, 'fieldsShow'])->name('vendor.fields.show');
    Route::put('/vendor/fields/{field}', [VendorController::class, 'fieldsUpdate'])->name('vendor.fields.update');
});
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
