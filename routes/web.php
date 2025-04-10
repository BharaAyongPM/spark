<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FieldTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
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
//CEK DUE DATE
Route::get('/check-expired-orders', [PaymentController::class, 'checkExpiredOrders']);


//ADMIN
Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('is_admin');
Route::get('admin/users', [AdminController::class, 'viewUsers'])->name('admin.users');
Route::get('admin/fields', [AdminController::class, 'viewFields'])->name('admin.fields');
Route::get('admin/orders', [AdminController::class, 'viewOrders'])->name('admin.orders');
Route::resource('admin/facilities', FacilityController::class)->except(['show', 'create', 'edit']);
Route::resource('admin/field_types', FieldTypeController::class)->except(['show', 'create', 'edit']);
Route::get('admin/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('admin/settings', [SettingController::class, 'store'])->name('settings.create');
Route::put('admin/settings/{id}', [SettingController::class, 'update'])->name('settings.update');
Route::delete('admin/settings/{id}', [SettingController::class, 'destroy'])->name('settings.delete');
//VENDOR
Route::middleware(['auth', 'role:PEMILIK'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');

    Route::get('/vendor/fields', [VendorController::class, 'fieldsIndex'])->name('vendor.fields.index');
    Route::post('/vendor/fields', [VendorController::class, 'fieldsStore'])->name('vendor.fields.store');
    Route::delete('/vendor/fields/{field}', [VendorController::class, 'fieldsDestroy'])->name('vendor.fields.destroy');
    Route::get('/vendor/fields/{field}', [VendorController::class, 'fieldsShow'])->name('vendor.fields.show');
    Route::put('/vendor/fields/{field}', [VendorController::class, 'fieldsUpdate'])->name('vendor.fields.update');

    //USER
    Route::get('/vendor', [VendorController::class, 'indexvendor'])->name('vendor.indexvendor');
    Route::post('/vendor/update-profile', [VendorController::class, 'updateProfile'])->name('vendor.updateProfile');
    Route::post('/vendor/update-password', [VendorController::class, 'updatePassword'])->name('vendor.updatePassword');
    Route::post('/vendor/update-rekening', [VendorController::class, 'updateRekening'])->name('vendor.updateRekening');
    //HARGA
    Route::get('/vendor/harga', [VendorController::class, 'hargaIndex'])->name('vendor.harga.index');
    Route::post('/vendor/harga/tambah', [VendorController::class, 'tambahHarga'])->name('vendor.harga.tambah');
    Route::post('/vendor/harga/update/{id}', [VendorController::class, 'updateHarga'])->name('vendor.harga.update');
    //JAM OPERASIONAL
    Route::get('/vendor/jam-operasional', [VendorController::class, 'jamOperasionalIndex'])->name('vendor.jamoperasional.index');
    Route::post('/vendor/jam-operasional/store', [VendorController::class, 'jamOperasionalStore'])->name('vendor.jamoperasional.store');
    Route::post('/vendor/jam-operasional/update/{id}', [VendorController::class, 'jamOperasionalUpdate'])->name('vendor.jamoperasional.update');
    Route::delete('/vendor/jam-operasional/destroy/{id}', [VendorController::class, 'jamOperasionalDestroy'])->name('vendor.jamoperasional.destroy');
});

//HOME
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/lapangan', [HomeController::class, 'indexfield'])->name('home.indexfield');
Route::get('/lapangan/{id}', [HomeController::class, 'show'])->name('home.field');
Route::post('/lapangan/{id}/slots', [HomeController::class, 'getSlots'])->name('home.field.slots');

//CHART
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
//chekout
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');
Route::post('/webhook/xendit', [PaymentController::class, 'handleXenditWebhook'])->name('webhook.xendit');
//Order
Route::get('/orders', [OrderController::class, 'index'])->name('orders')->middleware('auth');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');


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
// Route::get('/', function () {
//     return view('index');
// });

Route::get('/send-test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('ayongbhara01@gmail.com')->subject('Test Email');
    });

    return 'Email sent!';
});
