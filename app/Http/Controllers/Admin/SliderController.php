<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SliderController extends Controller implements HasMiddleware
{
    const Model_Directory = 'sliders';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-slider', only: ['index']),
            new Middleware('can:add-slider', only: ['create', 'store']),
            new Middleware('can:read-slider', only: ['show']),
            new Middleware('can:edit-slider', only: ['edit', 'update']),
            new Middleware('can:delete-slider', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $sliders = Slider::latest()
            ->with('translations')
            ->translatedIn($currentLang)->paginate(10)
            ->withQueryString();

        return view('admin.sliders.index', compact('sliders', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
