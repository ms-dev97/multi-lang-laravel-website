<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index() {
        $documents = Document::active()->latest()->withTranslation()->translatedIn(app()->getLocale())->paginate(9);
        return view('public.documents.index', compact('documents'));
    }

    public function show(Document $document) {
        if (!$document->isActive()) abort(404);

        $item = $document;
        $itemTrans = $item->translate();

        if (is_null($itemTrans)) abort(404);

        return view('public.documents.show', compact('item', 'itemTrans'));
    }
}
