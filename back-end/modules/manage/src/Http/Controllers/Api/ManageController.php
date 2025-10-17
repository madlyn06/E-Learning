<?php

namespace Modules\Manage\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Manage\Http\Resources\AdminDetailResource;
use Modules\Manage\Http\Resources\AdminResource;
use Modules\Manage\Http\Resources\BrandResource;
use Modules\Manage\Http\Resources\ServiceResource;
use Modules\Manage\Http\Resources\ClientResource;
use Modules\Manage\Http\Resources\FaqResource;
use Modules\Manage\Http\Resources\PageResource;
use Modules\Manage\Http\Resources\PortfolioCategoryResource;
use Modules\Manage\Http\Resources\PortfolioProjectResource;
use Modules\Manage\Http\Resources\TeamResource;
use Modules\Manage\Models\Brand;
use Modules\Manage\Models\Client;
use Modules\Manage\Models\FAQ;
use Modules\Manage\Models\Page;
use Modules\Manage\Models\PortfolioCategory;
use Modules\Manage\Models\PortfolioProject;
use Modules\Manage\Models\Team;
use Modules\Manage\Models\Service;
use Modules\Manage\Services\ManageService;
use Newnet\Acl\Models\Admin;
use Newnet\Cms\Http\Resources\CategoryResource;
use Newnet\Cms\Http\Resources\PostResource;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Utils\EloquentUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Models\Url;

class ManageController extends Controller
{
  protected ManageService $manageService;

  public function __construct(ManageService $manageService)
  {
    $this->manageService = $manageService;
  }

  /**
   * Get all services
   */
  public function getAllServices()
  {
    return ServiceResource::collection(Service::all());
  }

  /**
   * Get all services
   * @return array
   */
  public function getServices()
  {
    $limit = request('limit');
    $ids = request('ids');
    if ($limit) {
      $items = Service::whereIsActive(true)->paginate($limit);
    } elseif ($ids) {
      $items = Service::whereIsActive(true)->whereIn('id', json_decode($ids, true))->get();
    } else {
      $items = Service::whereIsActive(true)->get();
    }
    return ServiceResource::collection($items);
  }

  /**
   * Get service by slug
   */
  public function getServiceBySlug($slug)
  {
    $path = Common::convertSlug($slug);
    $url = Url::where(['urlable_type' => 'Modules\Manage\Models\Service', 'request_path' => $path])->first();
    if (!$url) {
      return response()->json([
        'message' => 'Service not found',
      ]);
    }
    $item = Service::findOrFail($url->urlable_id);
    return new ServiceResource($item);
  }

  /**
   * Get all clients
   */
  public function getClients()
  {
    if (!empty(request('limit'))) {
      $items = Client::whereIsActive(true)->paginate(request('limit'));
    } else {
      $items = Client::whereIsActive(true)->get();
    }
    return ClientResource::collection($items);
  }

  /**
   * Get all members
   */
  public function getMembers()
  {
    $items = Team::whereIsActive(true)->get();
    return TeamResource::collection($items);
  }

   /**
   * Get all brand
   * @return array
   */
  public function getBrands()
  {
    $limit = request('limit');
    if ($limit) {
      $items = Brand::whereIsActive(true)->paginate($limit);
    } else {
      $items = Brand::whereIsActive(true)->get();
    }
    return BrandResource::collection($items);
  }

  /**
   * Get all admin members
   */
  public function getAdminMembers()
  {
    $items = Admin::whereIsActive(true)->get();
    return AdminResource::collection($items);
  }

  /**
   * Get admin detail
   */
  public function getAdminDetail($slug)
  {
    $item = Admin::whereSlug($slug)->first();
    return new AdminDetailResource($item);
  }

  /**
   * Get randoms post
   */
  public function getRandomPosts()
  {
    return PostResource::collection(Post::inRandomOrder()->limit(4)->get());
  }

  /**
   * Get random category
   */
  public function getRandomCategory()
  {
    return CategoryResource::collection(Category::inRandomOrder()->limit(3)->get());
  }

  /**
   * Get all FAQs
   */
  public function getFaqs()
  {
    if (request('limit')) {
      return FaqResource::collection(FAQ::inRandomOrder()->whereIsActive(true)->limit(3)->get());
    }
    return FaqResource::collection(FAQ::whereIsActive(true)->get());
  }

  /**
   * Get all admin members
   */
  public function getAllPages()
  {
    $items = Page::whereIsActive(true)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get();
    return PageResource::collection($items);
  }

   /**
   * Get admin detail
   */
  public function getPageDetail($slug)
  {
    $item = EloquentUtils::transferModel($slug);
    if (!$item instanceof Page) {
      return response()->json([
        'message' => 'The model is not instance of Page',
      ], 400);
    }
    return new PageResource($item);
  }

  public function getAllPortfolioCategories(Request $request)
  {
    $type = $request->type;
    if ($type == 'all') {
      return PortfolioCategoryResource::collection(PortfolioCategory::whereIsActive(true)->get());
    }
    return PortfolioCategoryResource::collection(PortfolioCategory::whereIsActive(true)->whereCategoryId($type)->get());
  }

  public function getAllPortfolioProjects()
  {
    return PortfolioProjectResource::collection(PortfolioProject::whereIsActive(true)->get());
  }
}
