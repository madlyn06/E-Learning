<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Http\Requests\CategoryRequest;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Repositories\CategoryRepositoryInterface;
use Newnet\Cms\Repositories\Eloquent\CategoryRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\CategoryEvent;
use Newnet\Cms\Events\NewItemEvent;
use Newnet\Cms\Utils\EloquentUtils;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface|CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $items = $this->categoryRepository->paginateTree($request->input('max', 20));

        return view('cms::admin.category.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CATEGORY);

        $item = new Category();
        $item->is_active = true;
        $item->parent_id = $request->input('parent_id');

        return view('cms::admin.category.create', compact('item'));
    }

    public function store(CategoryRequest $request)
    {
        $item = $this->categoryRepository->create($request->all());

        Artisan::call('cache:clear');
        event(new NewItemEvent($item));

        return redirect()
            ->route('cms.admin.category.edit', [
                $item->id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('cms::category.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::CATEGORY);

        $item = $this->categoryRepository->find($id);

        return view('cms::admin.category.edit', compact('item'));
    }

    public function update($id, CategoryRequest $request)
    {
        $category = $this->categoryRepository->updateById($request->all(), $id);

        Artisan::call('cache:clear');
        event(new NewItemEvent($category));

        return back()->with('success', __('cms::category.notification.updated'));
    }

    public function moveUp($id)
    {
        $this->categoryRepository->moveUp($id);

        return redirect()
            ->route('cms.admin.category.index')
            ->with('success', __('cms::category.notification.updated'));
    }

    public function moveDown($id)
    {
        $this->categoryRepository->moveDown($id);

        return redirect()
            ->route('cms.admin.category.index')
            ->with('success', __('cms::category.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->categoryRepository->delete($id);

        Artisan::call('cache:clear');
        EloquentUtils::updateLatestItem(Category::class, 'migrate_category_id', 'category');

        if ($request->wantsJson()) {
            Session::flash('success', __('cms::category.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.category.index')
            ->with('success', __('cms::category.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $this->categoryRepository->deleteMultiple($request->ids);

        EloquentUtils::updateLatestItem(Category::class, 'migrate_category_id', 'category');
        Session::flash('success', __('cms::category.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
