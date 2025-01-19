<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle($role)
    {
        session(['user_role' => $role]);  // Menyimpan role di session
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        Log::info('Google callback reached.');
        try {
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google User:', (array) $googleUser);

            $roleName = session('user_role');  // Mengambil role dari session
            Log::info('Session Role:', ['role' => $roleName]);

            $user = User::firstOrCreate([
                'provider_id' => $googleUser->getId(),
                'provider' => 'google'
            ], [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar_url' => $googleUser->getAvatar(),
                'password' => Hash::make(uniqid()),  // Menghasilkan password random untuk keamanan
                'is_active' => true
            ]);

            Log::info('User Created/Found:', ['user_id' => $user->id]);

            // Menetapkan role
            if ($user->wasRecentlyCreated) {
                $role = Role::where('name_roles', $roleName)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                    Log::info('Role Attached to User:', ['role_id' => $role->id]);
                } else {
                    Log::error('Role not found', ['role_name' => $roleName]);
                }
            }

            Auth::login($user, true);
            Log::info('User Logged In:', ['user_id' => $user->id]);

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            Log::error('Error in Google Callback:', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $roleName = session('user_role');

            $user = User::firstOrCreate([
                'provider_id' => $facebookUser->getId(),
                'provider' => 'facebook'
            ], [
                'name' => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'avatar_url' => $facebookUser->getAvatar(),
                'password' => Hash::make(uniqid()),
                'is_active' => true
            ]);

            if ($user->wasRecentlyCreated) {
                $role = Role::where('name_roles', $roleName)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            }

            Auth::login($user, true);

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }
}
