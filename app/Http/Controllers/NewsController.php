<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index() {

    }

    public function show(News $news) {
        $item = $news;
        return view('public.news.show', compact('item'));
    }
}
