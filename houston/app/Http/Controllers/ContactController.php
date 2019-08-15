<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Message;
use Mail;

class ContactController extends Controller
{
    //
    public function show()
    {
        return view('contact');
    }

    public function postMessage(ContactUsRequest $request)
    {
        Message::create($request->all());

        $name = $request->input('name');
        $email = $request->input('email');
        $title = $request->input('title');
        $comment = $request->input('message');

        $data = [
            'name' => $name,
            'email' => $email,
            'title' => $title,
            'type' => 'Message',
            'comment' => $comment
        ];

        Mail::send('emails.new_comment_to_clooud', $data, function ($message) {
            $message->from('noreply@clooud.tv', 'Clooud Media')
                ->to('support@clooud.tv')
                ->subject('New Message');
        });

        flash('Message has been sent!', 'success');

        return back();
    }

    public function postDMCAMessage(ContactUsRequest $request)
    {
        $request->request->add(['title' => 'DMCA Complaint']);
        $request->request->add(['message' => 'IP send a complaint ' . $request->ip()]);

        Message::create($request->all());

        $name = $request->input('name');
        $email = $request->input('email');
        $title = 'by ' . $name;
        $comment = $request->input('infringing-urls');

        $data = [
            'name' => $name,
            'email' => $email,
            'title' => $title,
            'type' => 'DMCA Complaint',
            'comment' => $comment
        ];

        Mail::send('emails.new_comment_to_clooud', $data, function ($message) {
            $message->from('noreply@clooud.tv', 'Clooud Media')
                ->to('support@clooud.tv')
                ->subject('New DMCA Complaint');
        });

        flash('Your DMCA complaint has been made!', 'success');

        return back();
    }
}
