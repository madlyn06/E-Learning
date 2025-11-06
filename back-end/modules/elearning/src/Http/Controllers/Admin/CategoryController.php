<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\CategoryRequest;
use Modules\Elearning\Models\Category;
use Modules\Elearning\Repositories\CategoryRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class CategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $items = $this->categoryRepository->paginateTree($request->input('max', 20));

        return view('elearning::admin.category.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::CATEGORY);
        
        $categories = Category::where('parent_id', null)->pluck('name', 'id')->toArray();

        return view('elearning::admin.category.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $item = $this->categoryRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.categories.edit', [
                'category' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::category.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::CATEGORY);

        $item = $this->categoryRepository->find($id);
        $categories = Category::where('parent_id', null)
            ->where('id', '!=', $id)
            ->pluck('name', 'id')
            ->toArray();

        return view('elearning::admin.category.edit', compact('item', 'categories'));
    }

    public function update($id, CategoryRequest $request)
    {
        $this->categoryRepository->updateById($request->all(), $id);

        Artisan::call('cache:clear');

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.categories.index')
                ->with('success', __('elearning::category.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.categories.edit', [
                'category' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::category.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->categoryRepository->delete($id);

        Artisan::call('cache:clear');

        if ($request->wantsJson()) {
            Session::flash('success', __('cms::category.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }
        return redirect()
            ->route('elearning.admin.categories.index')
            ->with('success', __('elearning::category.notification.deleted'));
    }

     public function moveUp($id)
    {
        $this->categoryRepository->moveUp($id);

        return redirect()
            ->route('elearning.admin.categories.index')
            ->with('success', __('elearning::category.notification.updated'));
    }

    public function moveDown($id)
    {
        $this->categoryRepository->moveDown($id);

        return redirect()
            ->route('elearning.admin.categories.index')
            ->with('success', __('elearning::category.notification.updated'));
    }
}
