<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller implements HasMiddleware
{
    const Model_Directory = 'partners';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-partner', only: ['index']),
            new Middleware('can:add-partner', only: ['create', 'store']),
            new Middleware('can:read-partner', only: ['show']),
            new Middleware('can:edit-partner', only: ['edit', 'update']),
            new Middleware('can:delete-partner', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $partners = Partner::latest()
            ->with('translations')
            ->translatedIn($currentLang)->paginate(10)
            ->withQueryString();

        return view('admin.partners.index', compact('partners', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');

        return view('admin.partners.create', compact('langs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'image' => ['required', 'image', 'max:2000'],
            'link' => ['nullable', 'url'],
            'order' => ['nullable', 'numeric', 'integer'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $imagePath = null;

        DB::beginTransaction();

        try {
            if ($request->has('image')) {
                $imagePath = $request->file('image')->store(self::Model_Directory);
            }

            Partner::create([
                'image' => $imagePath,
                'order' => $validated['order'] ?? 0,
                'link' => $validated['link'],
                'status' => $request->has('status') ? true : false,
                $lang => [
                    'name' => $validated['name']
                ],
            ]);

            DB::commit();

            return redirect()->route('admin.partners.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
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
