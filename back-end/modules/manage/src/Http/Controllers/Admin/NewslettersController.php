<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Models\Newsletters;
use Modules\Manage\Repositories\SubcribeRepository;

class NewslettersController extends Controller
{
  protected SubcribeRepository $subcribeRepository;

  public function __construct(SubcribeRepository $subcribeRepository)
  {
    $this->subcribeRepository = $subcribeRepository;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $items = $this->subcribeRepository->paginate($request->input('max', 20));

    return view('manage::admin.newsletter.index', compact('items'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Newsletters  $newsletters
   * @return \Illuminate\Http\Response
   */
  public function show(Newsletters $newsletters)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Newsletters  $newsletters
   * @return \Illuminate\Http\Response
   */
  public function edit(Newsletters $newsletters)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Newsletters  $newsletters
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Newsletters $newsletters)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Newsletters  $newsletters
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    Newsletters::destroy($id);
    if ($request->ajax()) {
      Session::flash('success', __('Newsletter deleted successfully'));
      return response()->json([
        'success' => true,
      ]);
    }

    return redirect()
      ->route('manage.admin.newsletter.index')
      ->with('success', __('Newsletter deleted successfully'));
  }
}
