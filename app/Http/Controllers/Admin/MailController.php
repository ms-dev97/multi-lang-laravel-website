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
}
