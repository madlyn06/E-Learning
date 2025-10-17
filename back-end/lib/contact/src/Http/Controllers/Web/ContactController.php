<?php

namespace Newnet\Contact\Http\Controllers\Web;


use Illuminate\Routing\Controller;
use Newnet\Contact\Models\Contact;
use Newnet\Contact\Http\Requests\ContactRequest;
use Newnet\Contact\Events\SendEmailContactEvent;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact::web.contact.index');
    }

    public function sendMail(ContactRequest $request){
        $item = Contact::create($request->all());
        event(new SendEmailContactEvent($item));
        return redirect()->back()->with('contact-success', __('contact::message.notification.success'));
    }
}
