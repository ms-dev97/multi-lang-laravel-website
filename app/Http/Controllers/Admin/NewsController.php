<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewsExport;
use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class NewsController extends Controller implements HasMiddleware
{
    const Model_Directory = 'news';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-news', only: ['index']),
            new Middleware('can:add-news', only: ['create', 'store']),
            new Middleware('can:read-news', only: ['show']),
            new Middleware('can:edit-news', only: ['edit', 'update']),
            new Middleware('can:delete-news', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $news = News::latest()
            ->with('translations')
            ->translatedIn($currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');
        $categories = Category::active()->with('translations')->latest()->get();
        $programs = Program::active()->with('translations')->latest()->get();

        return view('admin.news.create', compact('langs', 'currentLang', 'categories', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:news,slug'],
            'image' => ['nullable', 'image', 'max:2000'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');
        $imagePath = null;
        $galleryItems = $request->gallery_input;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }

            $news = News::create([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $imagePath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            if ($news && $request->has('categories') && !is_null($request->categories[0])) {
                $news->categories()->sync($request->categories);
            }

            if ($news && $request->has('programs') && !is_null($request->programs[0])) {
                $news->programs()->sync($request->programs);
            }

            DB::commit();

            return redirect()->route('admin.news.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
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
    public function show(News $news)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $newsTrans = $news->translate($currentLang);
        $categories = $news->categories()->translatedIn($currentLang)->withTranslation()->get();

        if ($newsTrans == null) abort(404);

        return view('admin.news.show', compact('news', 'newsTrans', 'langs', 'currentLang', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news) {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $categories = Category::active()->with('translations')->latest()->get();
        $programs = Program::active()->with('translations')->latest()->get();
        $newsPrograms = $news->programs->pluck('id')->toArray();
        $newsCats = $news->categories->pluck('id')->toArray();

        return view('admin.news.edit', compact('langs', 'currentLang', 'news', 'categories', 'programs', 'newsPrograms', 'newsCats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news) {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('news')->ignore($news->id)],
            'image' => ['nullable', 'image'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $news->slug;
        $imagePath = $news->image;
        $newImagePath = null;
        $galleryItems = $request->gallery_input;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $newImagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }

            $news->update([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $newImagePath ?? $imagePath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            if ($request->has('categories') && !is_null($request->categories[0])) {
                $news->categories()->sync($request->categories);
            } else if (!$request->has('categories') || ($request->has('categories') && is_null($request->categories[0]))) {
                $news->categories()->detach();
            }

            if ($request->has('programs') && !is_null($request->programs[0])) {
                $news->programs()->sync($request->programs);
            } else if (!$request->has('programs') || ($request->has('programs') && is_null($request->programs[0]))) {
                $news->programs()->detach();
            }

            if (!is_null($newImagePath) && !is_null($imagePath)) {
                // Make sure to delete old images before commiting and after saving new images if there are no errors
                AdminHelpers::removeModelImage($imagePath);
            }

            DB::commit();

            return redirect()->route('admin.news.index', ['lang' => $lang])->with('success', 'تم التعديل بنجاح');
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
    public function destroy(News $news) {
        $news->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /** 
     * Export to excel
    */
    public function exportExcel() {
        return Excel::download(new NewsExport, 'news.xlsx');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $news = News::latest()
            ->whereTranslationLike('title', "%{$search}%", $currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'currentLang', 'langs', 'search'));
    }
}
