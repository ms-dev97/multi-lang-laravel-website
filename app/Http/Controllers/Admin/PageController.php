<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Laravel\Facades\Image;

class PageController extends Controller implements HasMiddleware
{
    const Model_Directory = 'pages';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-page', only: ['index']),
            new Middleware('can:add-page', only: ['create', 'store']),
            new Middleware('can:read-page', only: ['show']),
            new Middleware('can:edit-page', only: ['edit', 'update']),
            new Middleware('can:delete-page', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $pages = Page::latest()
            ->with('translations')
            ->translatedIn($currentLang)->paginate(10)
            ->withQueryString();

        return view('admin.pages.index', compact('pages', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');

        return view('admin.pages.create', compact('langs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => ['required', 'string', 'unique:pages,slug'],
            'image' => ['nullable', 'image', 'max:2000'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'view_name' => ['nullable', 'string'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $slug = Str::slug($validated['slug']);
        $imagePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = self::Model_Directory . '/' . Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                $optimizedImg = Image::read($request->file('image'))->encode(new AutoEncoder(quality: 90));
                Storage::disk('public')->put($imagePath, $optimizedImg);
            }

            Page::create([
                'slug' => $slug,
                'image' => $imagePath,
                'has_custom_view' => $request->has('has_custom_view') ? true : false,
                'view_name' => $validated['view_name'],
                'status' => $request->has('status') ? true : false,
                $lang => [
                    'name' => $validated['name'],
                    'excerpt' => $validated['excerpt'],
                    'body' => $validated['body'],
                ],
            ]);

            DB::commit();

            return redirect()->route('admin.pages.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
        } catch (\Throwable $th) {
            if (!is_null($imagePath)) {
                AdminHelpers::removeModelImage($imagePath);
            }

            DB::rollBack();
dd($th);
            return back()->with('error', 'حدث خطأ غير متوقع! حاول مرة اخرى.');
        }
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
