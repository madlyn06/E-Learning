<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Http\Requests\FaqRequest;
use Newnet\Cms\Models\FAQ;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\NewItemEvent;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\FaqRepositoryInterface;

class FaqController extends Controller
{
     /**
     * @var FaqRepositoryInterface
     */
    private $faqRepository;

    public function __construct(FaqRepositoryInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::POST);

        $post = Post::find($request->postId);

        $item = new FAQ();

        return view('cms::admin.faq.create', compact('item', 'post'));
    }

    public function store(FaqRequest $request)
    {
        $item = $this->faqRepository->create($request->all());
        event(new NewItemEvent($item->post));
        return redirect()
            ->route( 'cms.admin.cms-faqs.edit', $item)
            ->with('success', __('cms::faq.notification.created'));
    }

    public function edit(Request $request, $id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::POST);

        $item = $this->faqRepository->find($id);

        $post = $item->post;
        return view('cms::admin.faq.edit', compact('item', 'post'));
    }

    public function update($id, FaqRequest $request)
    {
        $item = $this->faqRepository->updateById($request->all(), $id);

        $post = $item->post;
        event(new NewItemEvent($post));
        event(new PostEvent($post));
        return back()->with('success', __('cms::faq.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $item = $this->faqRepository->find($id);
        $post = $item->post;

        $this->faqRepository->delete($id);
        event(new NewItemEvent($post));
        if ($request->wantsJson()) {
            Session::flash('success', __('cms::faq.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.cms-faqs.index')
            ->with('success', __('cms::faq.notification.deleted'));
    }
}
