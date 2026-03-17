<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Blade::if('premium', function ($module, $action = 'view') {
            $user = auth()->user();
            if (!$user || $user->role !== 'user') return true;
            
            $bumdes = $user->bumdes ?? \App\Models\Bumdesa::where('user_id', '=', $user->id)->first();
            if (!$bumdes) return true;

            return \App\Models\PremiumFeature::isAllowed($bumdes, $module, $action) === true;
        });
    }
}
