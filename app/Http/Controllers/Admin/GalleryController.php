<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-gallery', only: ['index']),
            new Middleware('can:add-gallery', only: ['create', 'store']),
            new Middleware('can:read-gallery', only: ['show']),
            new Middleware('can:edit-gallery', only: ['edit', 'update']),
            new Middleware('can:delete-gallery', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $galleries = Gallery::latest()
            ->with('translations')
            ->translatedIn($currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.galleries.index', compact('galleries', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');

        return view('admin.galleries.create', compact('langs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:galleries,slug'],
            'gallery_input' => ['required', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');
        $galleryItems = $request->gallery_input;

        Gallery::create([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            'photos' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
            $lang => [
                'title' => $validated['title'],
                'excerpt' => $validated['excerpt'],
                'body' => $validated['body'],
            ]
        ]);

        return redirect()->route('admin.galleries.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $galleryTrans = $gallery->translate($currentLang);

        if ($galleryTrans == null) abort(404);

        return view('admin.galleries.show', compact('gallery', 'galleryTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.galleries.edit', compact('langs', 'currentLang', 'gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('galleries')->ignore($gallery->id)],
            'gallery_input' => ['required', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $gallery->slug;
        $galleryItems = $request->gallery_input;

        $gallery->update([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            'photos' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
            $lang => [
                'title' => $validated['title'],
                'excerpt' => $validated['excerpt'],
                'body' => $validated['body'],
            ]
        ]);

        return redirect()->route('admin.galleries.index', ['lang' => $lang])->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $galleries = Gallery::latest()
            ->whereTranslationLike('title', "%{$search}%", $currentLang)
            ->paginate(15)
            ->withQueryString();
        return view('admin.galleries.index', compact('galleries', 'currentLang', 'langs', 'search'));
    }
}
