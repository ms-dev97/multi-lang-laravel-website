<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index() {
        $stories = Story::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.stories.index', compact('stories'));
    }

    public function show(Story $story) {
        if (!$story->isActive()) abort(404);

        $item = $story;
        $itemTrans = $item->translate();

        if (is_null($itemTrans)) abort(404);

        $related = Story::where('id', '!=', $story->id)
            ->active()
            ->latest()
            ->withTranslation()
            ->translatedIn(app()->getLocale())
            ->take(3)->get();

        return view('public.stories.show', compact('item', 'itemTrans', 'related'));
    }
}
