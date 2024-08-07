<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VideoController extends Controller implements HasMiddleware
{
    const Model_Directory = 'videos';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-video', only: ['index']),
            new Middleware('can:add-video', only: ['create', 'store']),
            new Middleware('can:read-video', only: ['show']),
            new Middleware('can:edit-video', only: ['edit', 'update']),
            new Middleware('can:delete-video', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $videos = Video::latest()
            ->with('translations')
            ->translatedIn($currentLang)->paginate(15)
            ->withQueryString();

        return view('admin.videos.index', compact('videos', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');

        return view('admin.videos.create', compact('langs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:videos,slug'],
            'link' => ['required', 'url'],
            'image' => ['nullable', 'image', 'max:2000'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');
        $imagePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }

            Video::create([
                'slug' => $slug,
                'link' => $validated['link'],
                'image' => $imagePath,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ],
            ]);

            DB::commit();

            return redirect()->route('admin.videos.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($imagePath)) {
                AdminHelpers::removeModelImage($imagePath);
            }

            DB::rollBack();

            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $videoTrans = $video->translate($currentLang);

        if ($videoTrans == null) abort(404);

        return view('admin.videos.show', compact('video', 'videoTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.videos.edit', compact('langs', 'currentLang', 'video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('videos')->ignore($video->id)],
            'link' => ['required', 'url'],
            'image' => ['nullable', 'image', 'max:2000'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $video->slug;
        $imagePath = $video->image;
        $newImagePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $newImagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }

            $video->update([
                'slug' => $slug,
                'link' => $validated['link'],
                'image' => $newImagePath ?? $imagePath,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ],
            ]);

            if (!is_null($newImagePath) && !is_null($imagePath)) {
                // Make sure to delete old images before commiting and after saving new images if there are no errors
                AdminHelpers::removeModelImage($imagePath);
            }

            DB::commit();

            return redirect()->route('admin.videos.index', ['lang' => $lang])->with('success', 'تم التعديل بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($newImagePath)) {
                AdminHelpers::removeModelImage($newImagePath);
            }

            DB::rollBack();

            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $videos = Video::latest()
            ->whereTranslationLike('title', "%{$search}%", $currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.videos.index', compact('videos', 'currentLang', 'langs', 'search'));
    }
}
