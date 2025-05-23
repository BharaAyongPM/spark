<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserSource;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        $roles = Role::all(); // Ambil semua role dari database
        return view('auth.register', compact('roles'));
    }
    public function showRegistrationFormPenyewa()
    {
        $role = 'Penyewa'; // Sesuaikan dengan nama role yang tepat di database Anda
        return view('auth.register_penyewa', compact('role'));
    }

    public function showRegistrationFormPemilik()
    {
        $role = 'Pemilik'; // Sesuaikan dengan nama role yang tepat di database Anda
        return view('auth.register_pemilik', compact('role'));
    }
    public function register(Request $request)
    {
        Log::info('START REGISTER');
        Log::info('Received register request with data: ', $request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
        ]);
        Log::info('✅ Validasi OK');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        Log::info('✅ User created: ' . $user->id);
        $role = Role::where('name_roles', $request->role)->firstOrFail();
        $user->roles()->attach($role->id);
        Log::info('✅ Role attached');
        if ($user && $user->id) {
            if ($user->email) {
                $this->sendOtp($user, 'email');
            } else {
                $this->sendOtp($user, 'phone');
            }

            return redirect()->route('verifyOtp.form', ['id' => $user->id]);
        } else {
            return back()->withErrors(['error' => 'Failed to create user.']);
        }
    }

    public function sendOtp($user, $type)
    {
        $otp = rand(100000, 999999);
        $message = "Kode OTP Anda adalah $otp. Berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.";
        $token = 'fTr8T6n6uVMvcEfD76gs';

        OtpCode::updateOrCreate(
            ['user_id' => $user->id],
            ['otp_code' => $otp, 'expiry_time' => now()->addMinutes(5), 'used' => false]
        );

        $recipient = ($type === 'phone') ? $user->phone : $user->email;

        try {
            if ($type == 'phone') {
                $client = new \GuzzleHttp\Client();
                $response = $client->post('https://api.fonnte.com/send', [
                    'form_params' => [
                        'target' => $recipient,
                        'message' => $message,
                    ],
                    'headers' => [
                        'Authorization' => "Bearer $token",
                    ],
                ]);
            } else {
                Mail::to($recipient)->send(new OtpMail($otp));
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send OTP: " . $e->getMessage());
            return false;
        }
    }
    public function showVerificationForm($id)
    {
        return view('auth.verify_otp', ['userId' => $id]);
    }
    public function resendOtp($id)
    {
        $user = User::findOrFail($id);
        $type = $user->email ? 'email' : 'phone'; // Asumsikan bahwa user mendaftar dengan email atau phone
        $this->sendOtp($user, $type);

        return back()->with('success', 'OTP has been resent. Please check your ' . $type . '.');
    }
    public function verifyOtp(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        // Cari record OTP berdasarkan user_id, bukan phone atau email
        $otpRecord = OtpCode::where('user_id', $user->id)->firstOrFail();

        if ($request->otp == $otpRecord->otp_code && now()->lessThan($otpRecord->expiry_time)) {
            // OTP valid
            Auth::login($user);
            return redirect('/');
        } else {
            // OTP invalid atau sudah kadaluarsa
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentukan apakah input adalah email atau phone
        $fieldType = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [$fieldType => $request->email_or_phone, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            // Autentikasi berhasil
            $user = Auth::user();

            // Periksa role user
            if ($user->roles->contains('name_roles', 'ADMIN')) {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->roles->contains('name_roles', 'PEMILIK')) {
                return redirect()->intended('vendor/dashboard');
            } elseif ($user->roles->contains('name_roles', 'PENYEWA')) {
                return redirect()->intended('/');
            }

            // Default redirect jika tidak ada role yang sesuai
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email_or_phone' => 'Credentials do not match.']);
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function checkSourceAndPreferences()
    {
        $user = auth()->user();

        // Cek apakah user sudah punya sumber dan preferensi
        $hasSource = UserSource::where('user_id', $user->id)->exists();
        $hasPreferences = UserPreference::where('user_id', $user->id)->exists();

        // Jika salah satu belum ada, arahkan ke form preferensi
        if (!($hasSource && $hasPreferences)) {
            return view('user.preferences-form');
        }

        // Kalau sudah lengkap, arahkan ke dashboard user
        return redirect()->intended('/');
    }


    public function saveSourceAndPreferences(Request $request)
    {
        $request->validate([
            'source_info' => 'required|string|max:255',
            'sports' => 'required|array|min:1'
        ]);

        $userId = auth()->id();

        // Simpan sumber info
        UserSource::updateOrCreate(
            ['user_id' => $userId],
            ['source' => $request->source_info]
        );

        // Simpan preferensi
        UserPreference::where('user_id', $userId)->delete();
        foreach ($request->sports as $sport) {
            UserPreference::create([
                'user_id' => $userId,
                'sport' => $sport
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function showprofil()
    {
        $user = auth()->user();
        return view('home.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
