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
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $request['name'] = $this->sanitizeUserInput($request->name);
        $request['subject'] = $this->sanitizeUserInput($request->subject);
        $request['phone_number'] = $this->sanitizeUserInput($request->phone_number);
        $request['email'] = $this->sanitizeUserInput($request->email);
        $request['message'] = $this->sanitizeUserInput($request->message);

        Mail::create($request->all());
        return redirect()->route('pages.show', 'contact-us')->with('success', __('app.message_sent'));
    }

    /**
     * Remove HTML tags, convert special chars to HTML entities and convert nl to br
     * @param string
     * @return string
     */
    private function sanitizeUserInput($input) {
        $input = strip_tags($input);
        $input = htmlentities($input, ENT_QUOTES, 'utf-8');
        $input = nl2br($input);
        return $input;
    }
}
