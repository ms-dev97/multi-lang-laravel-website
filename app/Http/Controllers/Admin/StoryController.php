<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Project;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoryController extends Controller implements HasMiddleware
{
    const Model_Directory = 'stories';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-story', only: ['index']),
            new Middleware('can:add-story', only: ['create', 'store']),
            new Middleware('can:read-story', only: ['show']),
            new Middleware('can:edit-story', only: ['edit', 'update']),
            new Middleware('can:delete-story', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $stories = Story::latest()
            ->with(['translations', 'program', 'project'])
            ->translatedIn($currentLang)->paginate(10)
            ->withQueryString();

        return view('admin.stories.index', compact('stories', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');
        $programs = Program::active()->with('translations')->latest()->get();
        $projects = Project::active()->with('translations')->latest()->get();

        return view('admin.stories.create', compact('langs', 'programs', 'projects', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug' => ['required', 'string', 'unique:stories,slug'],
            'image' => ['nullable', 'image', 'max:2000'],
            'gallery' => ['nullable', 'string'],
            'video_link' => ['nullable', 'url'],
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

            $stories = Story::create([
                'slug' => $slug,
                'status' => $request->has('status') ? true : false,
                'featured' => $request->has('featured') ? true : false,
                'image' => $imagePath,
                'gallery' => !is_null($galleryItems) ? explode(',', $galleryItems) : [],
                'type' => $request->type,
                'video_link' => $request->video_link,
                'program_id' =>  $request->has('program_id') ? $request->program_id : null,
                'project_id' =>  $request->has('project_id') ? $request->project_id : null,
                $lang => [
                    'title' => $validated['title'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ]
            ]);

            DB::commit();

            return redirect()->route('admin.stories.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
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
    public function show(Story $story)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $storyTrans = $story->translate($currentLang);

        if ($storyTrans == null) abort(404);

        return view('admin.stories.show', compact('story', 'storyTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $programs = Program::active()->translatedIn($currentLang)->with('translations')->latest()->get();
        $projects = Project::active()->translatedIn($currentLang)->with('translations')->latest()->get();

        return view('admin.stories.edit', compact('langs', 'currentLang', 'projects', 'programs', 'story'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
