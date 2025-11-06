<?php

namespace Newnet\Contact\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Contact\Models\Contact;
use Newnet\Contact\Http\Requests\ContactRequest;
use Newnet\Contact\Events\SendEmailContactEvent;

class ContactController extends Controller
{
    /**
     * Submit a contact form
     *
     * @param ContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(ContactRequest $request)
    {
        try {
            $item = Contact::create($request->all());
            event(new SendEmailContactEvent($item));
            
            return response()->json([
                'success' => true,
                'message' => __('contact::message.notification.success'),
                'data' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
