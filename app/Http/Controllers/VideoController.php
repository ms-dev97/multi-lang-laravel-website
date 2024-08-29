<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index() {
        $videos = Video::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.videos.index', compact('videos'));
    }

    public function show(Video $video) {
        if (!$video->isActive()) abort(404);

        $item = $video;
        $itemTrans = $item->translate();

        if (is_null($itemTrans)) abort(404);

        $related = Video::where('id', '!=', $video->id)
            ->active()
            ->latest()
            ->withTranslation()
            ->translatedIn(app()->getLocale())
            ->take(3)->get();

        return view('public.videos.show', compact('item', 'itemTrans', 'related'));
    }
}
