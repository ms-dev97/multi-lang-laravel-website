<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Laravel\Facades\Image;

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
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');

        return view('admin.sliders.create', compact('langs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'image' => ['required', 'image', 'max:2000'],
            'order' => ['nullable', 'numeric', 'integer'],
            'slider_location' => ['nullable', 'integer'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $imagePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = self::Model_Directory . '/' . Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                $optimizedImg = Image::read($request->file('image'))->encode(new AutoEncoder(quality: 90));
                Storage::disk('public')->put($imagePath, $optimizedImg);
            }

            Slider::create([
                'image' => $imagePath,
                'order' => $validated['order'],
                'slider_location' => $validated['slider_location'] ?? 1,
                'status' => $request->has('status') ? true : false,
                $lang => [
                    'title' => $validated['title']
                ],
            ]);

            DB::commit();

            return redirect()->route('admin.sliders.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
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
