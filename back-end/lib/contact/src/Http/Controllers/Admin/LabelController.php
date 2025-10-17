<?php

namespace Newnet\Contact\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Contact\Http\Requests\ContactRequest;
use Newnet\Contact\Repositories\LabelRepositoryInterface;

class LabelController extends Controller
{
    /**
     * @var LabelRepositoryInterface
     */
    protected $labelRepository;

    public function __construct(LabelRepositoryInterface $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }

    public function index(Request $request)
    {
        $items = $this->labelRepository->paginate($request->input('max', 20));

        return view('contact::admin.label.index', compact('items'));
    }

    public function create()
    {
        return view('contact::admin.label.create');
    }

    public function store(ContactRequest $request)
    {
        $item = $this->labelRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('contact.admin.label.edit', $item->id)
                ->with('success', __('contact::label.notification.created'));
        }

        return redirect()
            ->route('contact.admin.label.index')
            ->with('success', __('contact::label.notification.created'));
    }

    public function edit($id)
    {
        $item = $this->labelRepository->find($id);

        return view('contact::admin.label.edit', compact('item'));
    }

    public function update(ContactRequest $request, $id)
    {
        $item = $this->labelRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('contact.admin.label.edit', $item->id)
                ->with('success', __('contact::label.notification.updated'));
        }

        return redirect()
            ->route('contact.admin.label.index')
            ->with('success', __('contact::label.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->labelRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('contact::label.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('contact.admin.label.index')
            ->with('success', __('contact::label.notification.deleted'));
    }
}
