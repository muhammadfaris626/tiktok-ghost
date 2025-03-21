<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Webhook Sejoli diterima', $request->all());
        $user = Auth::user();
        $license = License::where('user_id', $user->id)->first();

        // Jika user sudah di halaman license-activation, lanjutkan
        if ($request->is('license-activation')) {
            return $next($request);
        }

        // Jika tidak memiliki lisensi atau expired, redirect
        if (!$license || !$license->is_active || $license->expired_at < now()) {
            return redirect('/license-activation');
        }
        return $next($request);
    }
}
