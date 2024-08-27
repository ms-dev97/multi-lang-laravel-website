<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index() {
        $announcements = Announcement::active()->latest()->withTranslation(app()->getLocale())->paginate(9);
        return view('public.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement) {
        if (!$announcement->isActive()) abort(404);

        $item = $announcement;
        $itemTrans = $item->translate(app()->getLocale(), true);

        if (is_null($itemTrans)) abort(404);

        $related = Announcement::where('id', '!=', $announcement->id)
            ->active()
            ->latest()
            ->withTranslation()
            ->translatedIn(app()->getLocale())
            ->take(3)->get();

        return view('public.announcements.show', compact('item', 'itemTrans', 'related'));
    }
}
