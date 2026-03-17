<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PremiumFeature;
use App\Models\Bumdesa;
use Illuminate\Support\Facades\Route;

class CheckPremiumFeature
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = Route::currentRouteName();
        
        // Hanya cek route user.*
        if (!str_starts_with($routeName, 'user.')) {
            return $next($request);
        }

        // Format route biasanya: user.{module}.{action}
        // Contoh: user.unit_usaha.store
        $parts = explode('.', $routeName);
        if (count($parts) < 3) {
            return $next($request);
        }

        $module = $parts[1];
        $routeAction = $parts[2];

        // Mapping route action ke Premium Action
        $actionMap = [
            'index' => 'view',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];

        $action = $actionMap[$routeAction] ?? 'view';

        // Ambil data bumdes (bisa dari slug atau auth)
        $bumdes = $request->user()->bumdes ?? Bumdesa::where('user_id', '=', $request->user()->id)->first();
        
        if (!$bumdes) {
            return $next($request);
        }

        $allowed = PremiumFeature::isAllowed($bumdes, $module, $action);

        if ($allowed !== true) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Fitur ini hanya tersedia untuk pengguna Premium.'], 403);
            }
            return abort(403, 'Akses Ditolak. Fitur ini hanya tersedia untuk pengguna Premium BUMDesa. Silakan hubungi admin atau upgrade paket Anda.');
        }

        return $next($request);
    }
}
