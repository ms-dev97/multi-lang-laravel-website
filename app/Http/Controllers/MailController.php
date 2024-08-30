<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function storeContactUs(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'subject' => 'required|string',
            'email' => 'email|nullable',
            'message' => 'string|required',
        ]);

        Mail::create($request->all());
        return redirect()->route('pages.show', 'contact-us')->with('success', __('app.message_sent'));
    }
}
