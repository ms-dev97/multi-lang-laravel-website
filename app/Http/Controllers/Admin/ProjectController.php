<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller implements HasMiddleware
{
    const Model_Directory = 'projects';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-project', only: ['index']),
            new Middleware('can:add-project', only: ['create', 'store']),
            new Middleware('can:read-project', only: ['show']),
            new Middleware('can:edit-project', only: ['edit', 'update']),
            new Middleware('can:delete-project', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $projects = Project::latest()->with(['translations', 'program'])->translatedIn($currentLang)->paginate(10)->withQueryString();

        return view('admin.projects.index', compact('projects', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');
        $programs = Program::active()->with('translations')->latest()->get();

        return view('admin.projects.create', compact('langs', 'programs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => ['nullable', 'string', 'unique:programs,slug'],
            'image' => ['nullable', 'image', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2000'],
            'gallery' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? Str::slug($validated['slug'], '-') : Str::slug($validated['title'], '-');
        $imagePath = null;
        $coverPath = null;
        $galleryItems = $request->gallery_input;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }
            if ($request->hasFile('cover')) {
                $coverPath = Storage::disk('public')->putFile(self::Model_Directory, $request->file('cover'));
            }

            $projects = Project::create([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $imagePath,
                'cover' => $coverPath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
                'program_id' =>  $request->has('program_id') ? $request->program_id : null,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            DB::commit();

            return redirect()->route('admin.projects.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($imagePath)) {
                AdminHelpers::removeModelImage($imagePath);
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
    public function show(Project $project)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $projectTrans = $project->translate($currentLang);

        if ($projectTrans == null) abort(404);

        return view('admin.projects.show', compact('project', 'projectTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $programs = Program::active()->translatedIn($currentLang)->with('translations')->latest()->get();

        return view('admin.projects.edit', compact('langs', 'currentLang', 'project', 'programs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => ['nullable', 'string', Rule::unique('projects')->ignore($project->id)],
            'image' => ['nullable', 'image', 'max:2000'],
            'cover' => ['nullable', 'image', 'max:2000'],
            'gallery' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = $validated['slug'] ? $validated['slug'] : $project->slug;
        $imagePath = $project->image;
        $coverPath = $project->cover;
        $newImagePath = null;
        $newCoverPath = null;
        $galleryItems = $request->gallery_input;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $newImagePath = AdminHelpers::storeModelImage($request, 'image', self::Model_Directory);
            }
            if ($request->hasFile('cover')) {
                $newCoverPath = Storage::disk('public')->putFile(self::Model_Directory, $request->file('cover'));
            }

            $project->update([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' =>$newImagePath ?? $imagePath,
                'cover' =>$newCoverPath ?? $coverPath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
                'program_id' =>  $request->has('program_id') ? $request->program_id : $project->program_id,
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

            if (!is_null($newCoverPath) && !is_null($coverPath)) {
                // Make sure to delete old images before commiting and after saving new images if there are no errors
                AdminHelpers::removeModelImage($coverPath);
            }

            DB::commit();

            return redirect()->route('admin.projects.index', ['lang' => $lang])->with('success', 'تم التعديل بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($newImagePath)) {
                AdminHelpers::removeModelImage($newImagePath);
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
    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    /**
     * Search for the search term
     */
    public function search(Request $request) {
        $search = $request->search;
        $currentLang = $request->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');

        $projects = Project::latest()->whereTranslationLike('title', "%{$search}%", $currentLang)->paginate(15)->withQueryString();
        return view('admin.projects.index', compact('projects', 'currentLang', 'langs', 'search'));
    }
}
