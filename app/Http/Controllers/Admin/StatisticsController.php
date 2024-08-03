<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminHelpers;
use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller implements HasMiddleware
{
    const Model_Directory = 'statistics';

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware()
    {
        return [
            new Middleware('can:browse-statistic', only: ['index']),
            new Middleware('can:add-statistic', only: ['create', 'store']),
            new Middleware('can:read-statistic', only: ['show']),
            new Middleware('can:edit-statistic', only: ['edit', 'update']),
            new Middleware('can:delete-statistic', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $langs = config('translatable.locales');
        $statistics = Statistic::latest()
            ->with('translations')
            ->translatedIn($currentLang)->paginate(10)
            ->withQueryString();

        return view('admin.statistics.index', compact('statistics', 'currentLang', 'langs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = config('translatable.locales');
        $currentLang = env('APP_LOCALE');

        return view('admin.statistics.create', compact('langs', 'currentLang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'image' => ['nullable', 'image', 'max:2000'],
            'order' => ['nullable', 'numeric', 'integer'],
            'number' => ['required', 'numeric'],
        ]);

        $lang = $request->lang ?? env('APP_LOCALE');
        $imagePath = null;

        DB::beginTransaction();

        try {
            if ($request->has('image')) {
                $imagePath = $request->file('image')->store(self::Model_Directory);
            }

            Statistic::create([
                'icon' => $imagePath,
                'order' => $validated['order'] ?? 0,
                'number' => $validated['number'],
                'status' => $request->has('status') ? true : false,
                $lang => [
                    'name' => $validated['name'],
                ],
            ]);

            DB::commit();

            return redirect()->route('admin.statistics.index', ['lang' => $lang])->with('success', 'تمت الاضافة بنجاح');
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
    public function show(Statistic $statistic)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');
        $statisticTrans = $statistic->translate($currentLang);

        if ($statisticTrans == null) abort(404);

        return view('admin.statistics.show', compact('statistic', 'statisticTrans', 'langs', 'currentLang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statistic $statistic)
    {
        $langs = config('translatable.locales');
        $currentLang = request()->lang ?? env('APP_LOCALE');

        return view('admin.statistics.edit', compact('langs', 'currentLang', 'statistic'));
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
