<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $slides = Slider::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->get();
        return view('public.home.index', compact('slides'));
    }
}
