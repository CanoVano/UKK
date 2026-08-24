<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Cek apakah user dengan google_id tersebut sudah ada
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }

            // 2. Jika tidak ada google_id, cek apakah emailnya sudah terdaftar
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Kaitkan google_id ke akun yang sudah ada
                $existingUser->update([
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                ]);

                Auth::login($existingUser);
                return redirect()->route('dashboard');
            }

            // 3. Jika email belum terdaftar sama sekali, buat user baru
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'password' => null, // Password nullable
                'role' => 'customer', // Role default
                'phone' => null,
            ]);

            Auth::login($newUser);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}
