<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        $roles = Role::all(); // Ambil semua role dari database
        return view('auth.register', compact('roles'));
    }

    // Handle registrasi user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Menyambungkan user dengan role
        $role = Role::where('name_roles', $request->role)->firstOrFail();
        $user->roles()->attach($role->id);

        // Mengirim OTP
        $this->sendOtp($request->phone);

        return redirect()->route('verifyOtp', ['id' => $user->id]);
    }

    // Mengirim OTP ke WhatsApp
    public function sendOtp($phone)
    {
        $otp = rand(100000, 999999);
        $token = 'fTr8T6n6uVMvcEfD76gs'; // Token API Anda
        $client = new Client();

        Otp::updateOrCreate(
            ['phone' => $phone],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(5)]
        );

        try {
            $response = $client->post('https://api.fonnte.com/send', [
                'form_params' => [
                    'target' => $phone,
                    'message' => "Kode OTP Anda adalah $otp. Berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.",
                ],
                'headers' => [
                    'Authorization' => $token,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                Log::error("Gagal mengirim OTP ke $phone. Respon: " . $response->getBody());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengirim OTP: " . $e->getMessage());
            return false;
        }
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $otpRecord = Otp::where('phone', $user->phone)->firstOrFail();

        if ($request->otp == $otpRecord->otp && now()->lessThan($otpRecord->expires_at)) {
            // OTP valid
            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['email' => 'Credentials do not match.']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}