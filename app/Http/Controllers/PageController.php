<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Page $page) {
        if (!$page->isActive()) abort(404);

        if (!is_null($page->view_name)) {
            return view('public.pages.' . $page->view_name, compact('page'));
        }
        return view('public.pages.index', compact('page'));
    }
}
