<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AnnouncementCategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-ad-category', only: ['index']),
            new Middleware('can:add-ad-category', only: ['create', 'store']),
            new Middleware('can:read-ad-category', only: ['show']),
            new Middleware('can:edit-ad-category', only: ['edit', 'update']),
            new Middleware('can:delete-ad-category', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $categories = AnnouncementCategory::latest()
            ->with('translations')
            ->translatedIn($currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.announcements_cats.index', compact('categories', 'langs', 'currentLang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        return view('admin.announcements_cats.create', compact('langs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:ad_categories,slug'],
        ]);
        
        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');

        $category = AnnouncementCategory::create([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            $lang => [
                'title' => $validated['title']
            ]
        ]);

        return redirect()->route('admin.announcement-categories.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(AnnouncementCategory $announcementCategory)
    {
        $category = $announcementCategory;
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $categoryTrans = $announcementCategory->translate($currentLang);

        if ($categoryTrans == null) abort(404);

        return view('admin.announcements_cats.show', compact('category', 'categoryTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnnouncementCategory $announcementCategory)
    {
        $category = $announcementCategory;
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.announcements_cats.edit', compact('langs', 'currentLang', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnnouncementCategory $announcementCategory)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('ad_categories')->ignore($announcementCategory->id)],
        ]);
        
        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $announcementCategory->slug;

        $announcementCategory->update([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            $lang => [
                'title' => $validated['title']
            ]
        ]);

        return redirect()->route('admin.announcement-categories.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnnouncementCategory $announcementCategory)
    {
        $announcementCategory->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }
}
