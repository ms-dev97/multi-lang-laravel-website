<?php

use App\Models\Mail;
use App\Models\Program;
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
if (!function_exists('getImgFromPath')) {
    function getImgFromPath($path) {
        return asset('storage/' . $path);
    }
}

// Get model thumbnail
if (!function_exists('getImgThumbnail')) {
    function getImgThumbnail($path) {
        if (is_null($path)) return null;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $path = Str::replaceLast('.'.$ext, '', $path) . '-sm.' . $ext;
        return asset('storage/' . $path);
    }
}

// Cache and get programs
if (!function_exists('getPrograms')) {
    function getPrograms() {
        $programs = Cache::remember('programs', 3600, function() {
            return Program::active()->latest()->with('translations')->take(5)->get();
        });

        return $programs;
    }
}

// Unread mails in the dashboard
if (!function_exists('checkUnreadMails')) {
    function checkUnreadMails() {
        return Cache::remember('checkUnreadMails', 600, function() {
            return Mail::firstWhere('is_read', false);
        });
    }
}