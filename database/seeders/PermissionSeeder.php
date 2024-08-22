<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generalPermissions = [['browse-admin', 'تصفح لوحة التحكم'], ['browse-media', 'تصفح الوسائط'], ['edit-settings', 'تعديل الأعدادات']];
        $tasks = [['browse', 'تصفح'], ['add', 'اضافة'], ['read', 'قراءة'], ['edit', 'تعديل'], ['delete', 'حذف']];
        $models = [
            ['ad', 'الاعلانات'], ['ad-category', 'اقسام الاعلانات'], ['category', 'الاقسام'], ['document', 'الوثائق'], ['doc-cat', 'اقسام الوثائق'], ['gallery', 'معرض الصور'], ['news', 'الاخبار'], ['page', 'الصفحات'], ['partner', 'الشركاء'], ['program', 'البرامج'], ['project', 'المشاريع'],
            ['slider', 'السلايدر'], ['statistic', 'الاحصاءات'], ['story', 'قصص النجاح'], ['user', 'المستخدمين'], ['video', 'الفيديوهات'], ['role', 'الأدوار']
        ];

        foreach($generalPermissions as $permission) {
            Permission::create([
                'name' => $permission[0],
                'display_name' => $permission[1],
            ]);
        }

        foreach ($models as $model) {
            foreach($tasks as $task) {
                $name = $task[0] . '-' . $model[0];
                $displayName = $task[1] . ' ' . $model[1];
                Permission::create([
                    'name' => $name,
                    'display_name' => $displayName,
                    'table_name' => $model[1],
                ]);
            }
        }
    }
}
