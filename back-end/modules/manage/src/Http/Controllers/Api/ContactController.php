<?php

namespace Modules\Manage\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Manage\Events\ContactEvent;
use Modules\Manage\Http\Requests\ContactRequest;
use Modules\Manage\Http\Requests\SubcribeRequest;
use Modules\Manage\Models\Contact;
use Modules\Manage\Models\Newsletters;

class ContactController extends Controller
{
  /**
   * Post contact
   */
  public function contact(ContactRequest $request)
  {
    $contact = Contact::create($request->all());
    event(new ContactEvent($contact));
    return response()->json([
      'message' => 'Thanks for you information, we will contact you as soon as possible!',
      'status' => true
    ]);
  }

  /**
   * Post subcribe
   */
  public function subcribe(SubcribeRequest $request)
  {
    $isSubcribed = Newsletters::whereEmail($request->email)->count();
    if ($isSubcribed > 0) {
      return response()->json([
        'message' => 'This email has been subcribed!',
        'status' => false
      ], Response::HTTP_BAD_REQUEST);
    }
    Newsletters::create($request->all());
    return response()->json([
      'message' => 'Subcribed successfully!',
      'status' => true
    ]);
  }
}
