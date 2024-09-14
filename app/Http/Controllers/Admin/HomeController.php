<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Program;
use App\Models\Project;
use App\Models\Story;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $newsCount = News::active()->count();
        $programsCount = Program::active()->count();
        $projectsCount = Project::active()->count();
        $announcementsCount = Announcement::active()->count();
        $documentsCount = Document::active()->count();
        $galleriesCount = Gallery::active()->count();
        $storiesCount = Story::active()->count();
        $videosCount = Video::active()->count();

        return view('admin.home.index', compact('newsCount', 'programsCount', 'projectsCount', 'announcementsCount', 'documentsCount', 'galleriesCount', 'storiesCount', 'videosCount'));
    }
}
