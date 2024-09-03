<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:edit-settings', only: ['index', 'updateAll']),
            new Middleware('can:edit-settings', only: ['store']),
            new Middleware('can:edit-settings', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Setting::get();
        $groupedSettings = [];

        foreach ($data as $d) {
            $groupedSettings[$d->group ?? 'general'][] = $d;
        }

        return view('admin.settings.index', compact('groupedSettings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string',
            'key' => 'required|string',
            'type' => 'required|string',
            'group' => 'required|string',
        ]);

        Setting::create($validated);

        // delete the cache
        Cache::forget('settings');

        return redirect()->route('admin.settings.index')->with('success', 'تم اضافة الإعداد');
    }

    /**
     * Update all the settings.
     */
    public function updateAll(Request $request)
    {
        $data = $request->all();
        $settings = Setting::get();

        foreach ($settings as $setting) {
            if (!array_key_exists($setting->key, $data)) continue;

            $value = $data[$setting->key];

            if ($setting->type == 'image') {
                if (!$request->hasFile($setting->key)) {
                    continue;
                } else {
                    if (!is_null($setting->value) && Storage::exists($setting->value)) {
                        Storage::delete($setting->value);
                    }
                    $value = $value->store('settings', 'public');
                }
            }

            $setting->value = $value;
            $setting->save();
        }

        // delete the cache
        Cache::forget('settings');

        return redirect()->route('admin.settings.index')->with('success', 'تم تعديل الاعدادات');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        if ($setting->type == 'image') {
            if (!is_null($setting->value) && Storage::exists($setting->value)) {
                Storage::delete($setting->value);
            }
        }

        $setting->delete();
        return redirect()->route('admin.settings.index')->with('success', 'تم حذف الإعداد');
    }
}
