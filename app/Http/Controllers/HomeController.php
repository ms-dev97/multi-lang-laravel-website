<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\Slider;
use App\Models\Statistic;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $locale = app()->getLocale();

        $slides = Slider::active()->latest()->withTranslation()->translatedIn($locale)->get();
        $news = News::active()->latest()->withTranslation()->translatedIn($locale)->take(3)->get();
        $programs = Program::active()->latest()->withTranslation()->translatedIn($locale)->take(3)->get();
        $statistics = Statistic::active()->orderBy('order')->withTranslation()->translatedIn($locale)->get();
        $partners = Partner::active()->orderBy('order')->withTranslation()->translatedIn($locale)->get();

        return view('public.home.index', compact('slides', 'news', 'programs', 'statistics', 'partners'));
    }
}
