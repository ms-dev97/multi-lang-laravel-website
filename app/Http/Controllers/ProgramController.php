<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index() {
        $programs = Program::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.programs.index', compact('programs'));
    }

    public function show(Program $program) {
        if (!$program->isActive()) abort(404);

        $item = $program;
        $itemTrans = $item->translate();
        if (is_null($itemTrans)) abort(404);

        $projects = $program->projects()->translatedIn(app()->getLocale())->withTranslation()->get();

        return view('public.programs.show', compact('item', 'itemTrans', 'projects'));
    }
}
