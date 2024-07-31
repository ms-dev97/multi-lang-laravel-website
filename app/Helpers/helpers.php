<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

// Get website setting
if (!function_exists('setting')) {
    function setting($key = null) {
        $settings = Cache::rememberForever('settings', function() {
            return Setting::get()->pluck('value', 'key')->toArray();
        });

        return is_null($key) ? $settings : $settings[$key];
    }
}