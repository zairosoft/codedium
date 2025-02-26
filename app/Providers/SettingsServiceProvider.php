<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Cache\Factory;
use App\Models\Settings;

use Illuminate\Support\Facades\Log;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Factory $cache, Settings $settings)
    {
        try {
            $settings = $cache->remember('settings', now()->addDays((int)env('CACHE_EXPIRE')), function () use ($settings) {
                return $settings->pluck('value', 'key')->all();
            });
            config()->set('settings', $settings);
        } catch (\Exception $ex) {
            Log::info($ex);
        }
    }
}
