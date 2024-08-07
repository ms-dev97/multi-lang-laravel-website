<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProgramController extends Controller implements HasMiddleware
{
    const Model_Directory = 'programs';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-program', only: ['index']),
            new Middleware('can:add-program', only: ['create', 'store']),
            new Middleware('can:read-program', only: ['show']),
            new Middleware('can:edit-program', only: ['edit', 'update']),
            new Middleware('can:delete-program', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $programs = Program::latest()
            ->with('translations')
            ->translatedIn($currentLang)->paginate(15)
            ->withQueryString();

        return view('admin.programs.index', compact('programs', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');

        return view('admin.programs.create', compact('langs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', 'unique:programs,slug'],
            'image' => ['nullable', 'image', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2000'],
            'icon' => ['nullable', 'image', 'max:2000'],
            'gallery' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');
        $imagePath = null;
        $iconPath = null;
        $coverPath = null;
        $galleryItems = $request->gallery_input;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }
            if ($request->hasFile('icon')) {
                $iconPath = Storage::disk('public')->putFile(self::Model_Directory, $request->file('icon'));
            }
            if ($request->hasFile('cover')) {
                $coverPath = Storage::disk('public')->putFile(self::Model_Directory, $request->file('cover'));
            }

            $program = Program::create([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $imagePath,
                'icon' => $iconPath,
                'cover' => $coverPath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            DB::commit();

            return redirect()->route('admin.programs.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($imagePath)) {
                AdminHelpers::removeModelImage($imagePath);
            }
            if (!is_null($iconPath)) {
                AdminHelpers::removeModelImage($iconPath);
            }
            if (!is_null($coverPath)) {
                AdminHelpers::removeModelImage($coverPath);
            }

            DB::rollBack();

            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $programTrans = $program->translate($currentLang);

        if ($programTrans == null) abort(404);

        return view('admin.programs.show', compact('program', 'programTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.programs.edit', compact('langs', 'currentLang', 'program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('programs')->ignore($program->id)],
            'image' => ['nullable', 'image', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2000'],
            'icon' => ['nullable', 'image', 'max:2000'],
            'gallery' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : $program->slug;
        $imagePath = $program->image;
        $iconPath = $program->icon;
        $coverPath = $program->cover;
        $newImagePath = null;
        $newIconPath = null;
        $newCoverPath = null;
        $galleryItems = $request->gallery_input;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $newImagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }
            if ($request->hasFile('icon')) {
                $newIconPath = Storage::disk('public')->putFile(self::Model_Directory, $request->file('icon'));
            }
            if ($request->hasFile('cover')) {
                $newCoverPath = Storage::disk('public')->putFile(self::Model_Directory, $request->file('cover'));
            }

            $program->update([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $newImagePath ?? $imagePath,
                'icon' => $newIconPath ?? $iconPath,
                'cover' => $newCoverPath ?? $coverPath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
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

            if (!is_null($newIconPath) && !is_null($iconPath)) {
                // Make sure to delete old images before commiting and after saving new images if there are no errors
                AdminHelpers::removeModelImage($iconPath);
            }

            if (!is_null($newCoverPath) && !is_null($coverPath)) {
                // Make sure to delete old images before commiting and after saving new images if there are no errors
                AdminHelpers::removeModelImage($coverPath);
            }

            DB::commit();

            return redirect()->route('admin.programs.index', ['lang' => $lang])->with('success', 'تم التعديل بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($newImagePath)) {
                AdminHelpers::removeModelImage($newImagePath);
            }

            if (!is_null($newIconPath)) {
                AdminHelpers::removeModelImage($newIconPath);
            }

            if (!is_null($newCoverPath)) {
                AdminHelpers::removeModelImage($newCoverPath);
            }

            DB::rollBack();

            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $programs = Program::latest()
            ->whereTranslationLike('title', "%{$search}%", $currentLang)
            ->paginate(15)
            ->withQueryString();

        return view('admin.programs.index', compact('programs', 'currentLang', 'langs', 'search'));
    }
}
