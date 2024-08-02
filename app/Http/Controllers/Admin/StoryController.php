<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Project;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
