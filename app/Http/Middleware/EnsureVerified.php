<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate sensitive modules (e.g. Monitoring) behind the MyDigital ID e-KYC tier.
 * Basic OTP users are redirected with an explanation.
 */
class EnsureVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_verified) {
            return redirect()->route('profile.show')->with(
                'status',
                'Modul ini hanya untuk pengguna yang telah disahkan melalui MyDigital ID (e-KYC).'
            );
        }

        return $next($request);
    }
}
