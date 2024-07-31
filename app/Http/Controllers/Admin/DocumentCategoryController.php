<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentCategoryController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-doc-cat', only: ['index']),
            new Middleware('can:add-doc-cat', only: ['create', 'store']),
            new Middleware('can:read-doc-cat', only: ['show']),
            new Middleware('can:edit-doc-cat', only: ['edit', 'update']),
            new Middleware('can:delete-doc-cat', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lang = request()->lang ?? env('APP_LOCALE');
        $categories = DocumentCategory::latest()->with('translations')->translatedIn($lang)->paginate(15)->withQueryString();

        return view('admin.document_cats.index', compact('categories', 'lang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        return view('admin.document_cats.create', compact('langs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => ['nullable', 'string', 'unique:document_categories,slug'],
        ]);
        
        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');

        $category = DocumentCategory::create([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            $lang => [
                'title' => $validated['title']
            ]
        ]);

        return redirect()->route('admin.document-categories.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentCategory $documentCategory)
    {
        $category = $documentCategory;
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $categoryTrans = $documentCategory->translate($currentLang);

        if ($categoryTrans == null) abort(404);

        return view('admin.document_cats.show', compact('category', 'categoryTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, DocumentCategory $documentCategory)
    {
        $category = $documentCategory;
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.document_cats.edit', compact('langs', 'currentLang', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentCategory $documentCategory)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => ['nullable', 'string', Rule::unique('ad_categories')->ignore($documentCategory->id)],
        ]);
        
        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? $validated['slug'] : $documentCategory->slug;

        $documentCategory->update([
            'slug' => $slug,
            'status' => $request->has('status') ? true : false,
            'featured' => $request->has('featured') ? true : false,
            $lang => [
                'title' => $validated['title']
            ]
        ]);

        return redirect()->route('admin.document-categories.index')->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentCategory $documentCategory)
    {
        $documentCategory->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }
}
