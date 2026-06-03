<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user    = Auth::user()->loadCount('posts');
        $reviews = $user->reviewsReceived()->with('reviewer')->latest()->take(10)->get();
        $history = $user->posts()->whereIn('status',['completed','closed','expired'])->latest()->take(20)->get();
        return view('profile.show', compact('user','reviews','history'));
    }

    public function updateLocation(Request $request)
    {
        $data = $request->validate([
            'home_lat'      => ['required','numeric','between:-90,90'],
            'home_lng'      => ['required','numeric','between:-180,180'],
            'address_label' => ['nullable','string','max:120'],
        ]);
        Auth::user()->update($data);
        // Mark onboarded after location set
        if (!Auth::user()->hasOnboarded()) {
            Auth::user()->update(['onboarded_at' => now()]);
        }
        return redirect()->route('profile.show')->with('status','Lokasi anda telah dikemas kini.');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate(['name' => ['required','string','max:60']]);
        Auth::user()->update($data);
        return back()->with('status','Profil dikemas kini.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => ['required','image','max:2048']]);
        $user = Auth::user();
        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        $path = $request->file('avatar')->store('avatars','public');
        $user->update(['avatar' => $path]);
        return back()->with('status','Gambar profil dikemas kini.');
    }

    public function completeOnboarding()
    {
        Auth::user()->update(['onboarded_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function verifyIdentity(Request $request)
    {
        return redirect()->route('profile.show')->with('status','Pengesahan MyDigital ID (e-KYC) akan datang. Buat masa ini, lencana "Disahkan" diberikan kepada pengguna yang telah mengesahkan identiti.');
    }
}
