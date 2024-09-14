<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Program;
use App\Models\Project;
use App\Models\Story;
use App\Models\Video;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

class SearchController extends Controller
{
    public function index(Request $request) {
        $q = $request->q;
        $locale = app()->getLocale();

        $results = Search::new()
            ->add(News::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Announcement::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Document::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Gallery::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Program::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Project::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Story::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->add(Video::active()->withTranslation()->translatedIn($locale), ['translations.title'])
            ->beginWithWildcard()
            ->paginate(5)
            ->search($q);

        return view('public.search.index', compact('results', 'q'));
    }
}
