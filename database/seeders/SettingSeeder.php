<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            ['key' => 'facebook_id', 'display_name' => 'معرف صفحة الفيسبوك', 'type' => 'text', 'group' => 'info'],
            ['key' => 'facebook_link', 'display_name' => 'رابط صفحة الفيسبوك', 'type' => 'text', 'group' => 'info'],
            ['key' => 'twitter_id', 'display_name' => 'معرف صفحة تويتر', 'type' => 'text', 'group' => 'info'],
            ['key' => 'twitter_link', 'display_name' => 'رابط صفحة تويتر', 'type' => 'text', 'group' => 'info'],
            ['key' => 'linkedin_link', 'display_name' => 'رابط صفحة لنكد ان', 'type' => 'text', 'group' => 'info'],
            ['key' => 'instagram_link', 'display_name' => 'رابط انستجرام', 'type' => 'text', 'group' => 'info'],
            ['key' => 'header_logo', 'display_name' => 'شعار الموقع (الهيدر)', 'type' => 'image', 'group' => 'backgrounds'],
            ['key' => 'footer_logo', 'display_name' => 'شعار الموقع (الفوتر)', 'type' => 'image', 'group' => 'backgrounds'],
        ]);
    }
}
