<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Program;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $slides = Slider::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->get();
        $news = News::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->take(3)->get();
        $programs = Program::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->take(3)->get();
        return view('public.home.index', compact('slides', 'news', 'programs'));
    }
}
