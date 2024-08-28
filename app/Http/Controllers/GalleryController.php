<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index() {
        $galleries = Gallery::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.galleries.index', compact('galleries'));
    }

    public function show(Gallery $gallery) {
        if (!$gallery->isActive()) abort(404);

        $item = $gallery;
        $itemTrans = $item->translate();

        if (is_null($itemTrans)) abort(404);

        return view('public.galleries.show', compact('item', 'itemTrans'));
    }
}
