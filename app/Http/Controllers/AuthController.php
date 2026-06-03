<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'regex:/^01[0-9]{8,9}$/'],
            'name'  => ['nullable', 'string', 'max:60'],
        ], [], ['phone' => 'nombor telefon', 'name' => 'nama']);

        $phone = preg_replace('/[^0-9]/', '', $data['phone']);

        $user = User::where('phone', $phone)->first();

        if (! $user) {
            if (empty($data['name'])) {
                return back()->withInput()
                    ->withErrors(['name' => 'Sila masukkan nama untuk mendaftar.']);
            }

            $user = User::create([
                'name'              => $data['name'],
                'phone'             => $phone,
                'phone_verified_at' => now(),
                'is_verified'       => false,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if (! $user->hasLocation()) {
            return redirect()->route('profile.show')
                ->with('status', 'Selamat datang ke BantuJiran! Sila tetapkan lokasi anda.');
        }

        return redirect()->route('feed.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
