<?php

namespace Newnet\Customer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Customer\Http\Requests\GroupRequest;
use Newnet\Customer\Repositories\GroupRepositoryInterface;

class GroupController extends Controller
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function index(Request $request)
    {
        $items = $this->groupRepository->paginate($request->input('max', 20));

        return view('customer::admin.group.index', compact('items'));
    }

    public function create()
    {
        return view('customer::admin.group.create');
    }

    public function store(GroupRequest $request)
    {
        $item = $this->groupRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('customer.admin.group.edit', $item->id)
                ->with('success', __('customer::group.notification.created'));
        }

        return redirect()
            ->route('customer.admin.group.index')
            ->with('success', __('customer::group.notification.created'));
    }

    public function edit($id)
    {
        $item = $this->groupRepository->find($id);

        return view('customer::admin.group.edit', compact('item'));
    }

    public function update(GroupRequest $request, $id)
    {
        $item = $this->groupRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('customer.admin.group.edit', $item->id)
                ->with('success', __('customer::group.notification.updated'));
        }

        return redirect()
            ->route('customer.admin.group.index')
            ->with('success', __('customer::group.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->groupRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('customer::group.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('customer.admin.group.index')
            ->with('success', __('customer::group.notification.deleted'));
    }
}
