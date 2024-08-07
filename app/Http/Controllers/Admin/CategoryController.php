<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-category', only: ['index']),
            new Middleware('can:add-category', only: ['create', 'store']),
            new Middleware('can:read-category', only: ['show']),
            new Middleware('can:edit-category', only: ['edit', 'update']),
            new Middleware('can:delete-category', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lang = request()->lang ?? env('APP_LOCALE');
        $categories = Category::latest()
            ->with('translations')
            ->translatedIn($lang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'lang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        return view('admin.categories.create', compact('langs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:categories,slug']
        ]);
        
        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');

        $category = Category::create([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            $lang => [
                'title' => $validated['title']
            ]
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $categoryTrans = $category->translate($currentLang);

        if ($categoryTrans == null) abort(404);

        return view('admin.categories.show', compact('category', 'categoryTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.categories.edit', compact('langs', 'currentLang', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('categories')->ignore($category->id)]
        ]);
        
        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $category->slug;

        $category->update([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            $lang => [
                'title' => $validated['title']
            ]
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }
}
