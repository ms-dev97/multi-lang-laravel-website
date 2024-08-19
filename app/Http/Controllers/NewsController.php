<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index() {
        $news = News::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.news.index', compact('news'));
    }

    public function show(News $news) {
        if (!$news->isActive()) abort(404);

        $item = $news;
        $related = News::where('id', '!=', $news->id)
            ->active()
            ->latest()
            ->withTranslation()
            ->translatedIn(app()->getLocale())
            ->take(3)->get();

        return view('public.news.show', compact('item', 'related'));
    }
}
