<?php

namespace Newnet\Contact\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Contact\Http\Requests\ContactRequest;
use Newnet\Contact\Models\Contact;
use Newnet\Contact\Repositories\ContactRepositoryInterface;

class ContactController extends Controller
{
    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function index(Request $request)
    {

        $items = Contact::with('label');
        if ($request->search) {
            $items->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');

            });
        }
        if ($request->label) {
            $items = $items->where('label_id', $request->label);
        }
        $items = $items->paginate(20);
        return view('contact::admin.contact.index', compact('items'));
    }

    public function create()
    {
        return view('contact::admin.contact.create');
    }

    public function store(ContactRequest $request)
    {
        $item = $this->contactRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('contact.admin.contact.edit', $item->id)
                ->with('success', __('contact::contact.notification.created'));
        }

        return redirect()
            ->route('contact.admin.contact.index')
            ->with('success', __('contact::contact.notification.created'));
    }

    public function edit($id)
    {
        $item = $this->contactRepository->find($id);

        return view('contact::admin.contact.edit', compact('item'));
    }

    public function update(ContactRequest $request, $id)
    {
        $item = $this->contactRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('contact.admin.contact.edit', $item->id)
                ->with('success', __('contact::contact.notification.updated'));
        }

        return redirect()
            ->route('contact.admin.contact.index')
            ->with('success', __('contact::contact.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->contactRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('contact::contact.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('contact.admin.contact.index')
            ->with('success', __('contact::contact.notification.deleted'));
    }
}
