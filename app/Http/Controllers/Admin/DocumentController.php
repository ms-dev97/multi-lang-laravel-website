<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentController extends Controller implements HasMiddleware
{
    const Model_Directory = 'documents';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-document', only: ['index']),
            new Middleware('can:add-document', only: ['create', 'store']),
            new Middleware('can:read-document', only: ['show']),
            new Middleware('can:edit-document', only: ['edit', 'update']),
            new Middleware('can:delete-document', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $documents = Document::latest()
            ->with(['translations', 'category'])
            ->translatedIn($currentLang)
            ->paginate(10)
            ->withQueryString();

        return view('admin.documents.index', compact('documents', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');
        $categories = DocumentCategory::active()->with('translations')->latest()->get();

        return view('admin.documents.create', compact('langs', 'currentLang', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:documents,slug'],
            'file' => ['exclude_with:get_from_link', 'required', 'file'],
            'link' => ['exclude_without:get_from_link', 'required', 'url'],
            'image' => ['nullable', 'image', 'max:2000'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');
        $filePath = null;
        $imagePath = null;
        

        DB::beginTransaction();

        try {
            if (!$request->has('get_from_link') && $request->has('file')) {
                $filePath = $request->file('file')->store(self::Model_Directory);
            }

            // Get image from PDF
            if ($request->has('img_from_pdf') && !is_null($filePath)) {
                $imagePath = AdminHelpers::convertPDFtoJPG(self::Model_Directory, $filePath);
            }

            // Get image from input
            if (!$request->has('img_from_pdf') && $request->has('image')) {
                $imagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }

            $document = Document::create([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'get_from_link' => $request->has('get_from_link') ? true : false,
                'image' => $imagePath,
                'path' => $filePath,
                'link' => $request->has('get_from_link') ? $validated['link'] : null,
                'document_category_id' => $request->category_id ?? null,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            DB::commit();

            return redirect()->route('admin.documents.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

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
    public function show(Document $document)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $documentTrans = $document->translate($currentLang);

        if ($documentTrans == null) abort(404);

        return view('admin.documents.show', compact('document', 'documentTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $categories = DocumentCategory::active()
            ->with('translations')
            ->latest()
            ->get();

        return view('admin.documents.edit', compact('langs', 'currentLang', 'document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => ['required', 'string', Rule::unique('documents')->ignore($document->id)],
            'file' => ['exclude_with:get_from_link', 'nullable', 'file'],
            'link' => ['exclude_without:get_from_link', 'required', 'url'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $document->slug;
        $filePath = $document->file;
        $imagePath = $document->image;
        $newFilePath = null;
        $newImagePath = null;

        DB::beginTransaction();

        try {
            if (!$request->has('get_from_link') && $request->has('file')) {
                $newFilePath = $request->file('file')->store(self::Model_Directory);

                $newImagePath = AdminHelpers::convertPDFtoJPG(self::Model_Directory, $newFilePath);
            }

            $document->update([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'get_from_link' => $request->has('get_from_link') ? true : false,
                'image' => $newImagePath ?? $imagePath,
                'path' => $newFilePath ?? $filePath,
                'link' => $validated['link'],
                'document_category_id' => $request->category_id,
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

            if (!is_null($newFilePath) && !is_null($filePath) && Storage::disk('public')->exists($filePath)) {
                // Make sure to delete old file before commiting and after saving new file if there are no errors
                Storage::disk('public')->delete($filePath);
            }

            DB::commit();

            return redirect()->route('admin.documents.index', ['lang' => $lang])->with('success', 'تمت التعديل بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($newFilePath)) {
                Storage::disk('public')->delete($newFilePath);
            }

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
    public function destroy(Document $document)
    {
        $document->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $documents = Document::latest()
            ->whereTranslationLike('title', "%{$search}%", $currentLang)
            ->paginate(15)
            ->withQueryString();
        return view('admin.documents.index', compact('documents', 'currentLang', 'langs', 'search'));
    }
}
