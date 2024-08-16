<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function index() {
        $mails = Mail::latest()->paginate(15);
        return view('admin.mails.index', compact('mails'));
    }

    public function show(Mail $mail) {
        if ($mail->is_read == false) {
            $mail->update(['is_read' => true]);
        }
        return view('admin.mails.show', compact('mail'));
    }

    public function destroy(Mail $mail) {
        $mail->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function search(Request $request) {
        $search = $request->search;

        $mails = Mail::latest()
            ->where('subject', 'like', "%{$search}%")
            ->paginate(15)
            ->withQueryString();

        return view('admin.mails.index', compact('mails', 'search'));
    }
}
