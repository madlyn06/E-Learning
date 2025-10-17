<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\ReplyEmailEvent;
use Modules\Manage\Models\Contact;
use Modules\Manage\Repositories\ContactRepository;

class ContactController extends Controller
{
  protected ContactRepository $contactRepository;

  public function __construct(ContactRepository $contactRepository)
  {
    $this->contactRepository = $contactRepository;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $items = $this->contactRepository->paginate($request->input('max', 20));
    return view('manage::admin.contact.index', compact('items'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function postReply(Request $request)
  {
    event(new ReplyEmailEvent($request->all()));
    $contactItem = Contact::find($request->id);
    $contactItem->update([
      'status' => 'replied',
    ]);
    return response()->json([
      'message' => 'Reply successfully',
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Client  $client
   * @return \Illuminate\Http\Response
   */
  public function reply($id)
  {
    $item = Contact::find($id);
    dd($item);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Client  $client
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    Contact::destroy($id);
    if ($request->ajax()) {
      Session::flash('success', __('Contact deleted successfully'));
      return response()->json([
        'success' => true,
      ]);
    }

    return redirect()
      ->route('manage.admin.contact.index')
      ->with('success', __('Contact deleted successfully'));
  }
}
