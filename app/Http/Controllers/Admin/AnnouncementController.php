<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller implements HasMiddleware
{
    const Model_Directory = 'announcements';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-ad', only: ['index']),
            new Middleware('can:add-ad', only: ['create', 'store']),
            new Middleware('can:read-ad', only: ['show']),
            new Middleware('can:edit-ad', only: ['edit', 'update']),
            new Middleware('can:delete-ad', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $announcements = Announcement::latest()
            ->with(['translations', 'category'])
            ->translatedIn($currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');
        $categories = AnnouncementCategory::active()->with('translations')->latest()->get();

        return view('admin.announcements.create', compact('langs', 'categories', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'image' => ['nullable', 'image', 'max:2000'],
            'file' => ['file', 'max:2000'],
            'apply_link' => ['nullable', 'url'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $imagePath = null;
        $filePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store(self::Model_Directory);
            }

            $announcements = Announcement::create([
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $imagePath,
                'file' => $filePath,
                'apply_link' => $request->apply_link,
                'ad_category_id' =>  $request->has('ad_category_id') ? $request->ad_category_id : null,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            DB::commit();

            return redirect()->route('admin.announcements.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($imagePath)) {
                AdminHelpers::removeModelImage($imagePath);
            }
            if (!is_null($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::rollBack();

            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $announcementTrans = $announcement->translate($currentLang);

        if ($announcementTrans == null) abort(404);

        return view('admin.announcements.show', compact('announcement', 'announcementTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $categories = AnnouncementCategory::active()->with('translations')->latest()->get();

        return view('admin.announcements.edit', compact('langs', 'currentLang', 'announcement', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required',
            'image' => ['nullable', 'image', 'max:2000'],
            'file' => ['file', 'max:2000'],
            'apply_link' => ['nullable', 'url'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $imagePath = $announcement->image;
        $filePath = $announcement->file;
        $newImagePath = null;
        $newFilePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $newImagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }

            if ($request->hasFile('file')) {
                $newFilePath = $request->file('file')->store(self::Model_Directory);
            }

            $announcement->update([
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $newImagePath ?? $imagePath,
                'file' => $newFilePath ?? $filePath,
                'apply_link' => $request->apply_link,
                'ad_category_id' =>  $request->ad_category_id,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            if (!is_null($newImagePath) && !is_null($imagePath)) {
                // Make sure to delete old images before commiting and after saving new images if there are no errors
                AdminHelpers::removeModelImage($imagePath);
            }

            if (!is_null($newFilePath) && !is_null($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();

            return redirect()->route('admin.announcements.index', ['lang' => $lang])->with('success', 'تم التعديل بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($newImagePath)) {
                AdminHelpers::removeModelImage($newImagePath);
            }
            if (!is_null($newFilePath)) {
                Storage::disk('public')->delete($newFilePath);
            }

            DB::rollBack();

            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $announcements = Announcement::latest()->whereTranslationLike('title', "%{$search}%", $currentLang)->paginate(15)->withQueryString();
        return view('admin.announcements.index', compact('announcements', 'currentLang', 'langs', 'search'));
    }
}
