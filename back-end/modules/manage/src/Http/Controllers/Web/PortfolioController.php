<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\PortfolioCategory;
use Modules\Manage\Models\PortfolioProject;

class PortfolioController extends Controller
{
    public function categoryDetail($id)
    {
        $item = PortfolioCategory::find($id);

        return view('manage::web.portfolio-category.detail', compact('item'));
    }

    public function projectDetail($id)
    {
        $item = PortfolioProject::find($id);

        return view('manage::web.portfolio-project.detail', compact('item'));
    }
}
