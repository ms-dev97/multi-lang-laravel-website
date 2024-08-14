<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


// Get website setting
if (!function_exists('setting')) {
    function setting($key = null) {
        $settings = Cache::rememberForever('settings', function() {
            return Setting::get()->pluck('value', 'key')->toArray();
        });

        return is_null($key) ? $settings : $settings[$key];
    }
}

// Get model image
if (!function_exists('model_image')) {
    function model_image($path) {
        return asset('storage/' . $path);
    }
}

// Get model thumbnail
if (!function_exists('model_thumbnail')) {
    function model_thumbnail($path) {
        if (is_null($path)) return null;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $path = Str::replaceLast('.'.$ext, '', $path) . '-sm.' . $ext;
        return asset('storage/' . $path);
    }
}