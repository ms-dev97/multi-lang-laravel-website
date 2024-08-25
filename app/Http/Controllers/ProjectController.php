<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.projects.index', compact('projects'));
    }

    public function show(Project $project) {
        if (!$project->isActive()) abort(404);

        $item = $project;
        $itemTrans = $item->translate();

        if (is_null($itemTrans)) abort(404);

        return view('public.projects.show', compact('item', 'itemTrans'));
    }
}
