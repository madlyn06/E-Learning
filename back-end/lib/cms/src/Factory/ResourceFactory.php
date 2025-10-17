<?php

namespace Newnet\Cms\Factory;

use Modules\Ecommerce\Http\Resources\ProductDetailResource;
use Modules\Ecommerce\Models\Product;
use Modules\Manage\Http\Resources\AdminResource;
use Modules\Manage\Http\Resources\FileDetailResource;
use Modules\Manage\Http\Resources\PortfolioProjectResource;
use Modules\Manage\Http\Resources\ServiceResource;
use Modules\Manage\Models\File;
use Modules\Manage\Models\Page as ModelsPage;
use Modules\Manage\Models\PortfolioProject;
use Modules\Manage\Models\Service;
use Newnet\Acl\Models\Admin;
use Newnet\Cms\Exceptions\BlogException;
use Newnet\Cms\Factory\Clients\CategoryDetailClient;
use Newnet\Cms\Factory\Clients\PostDetailClient;
use Newnet\Cms\Http\Resources\PageResource;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Page;
use Newnet\Cms\Models\Post;
use Newnet\Tag\Http\Resources\TagResource;
use Newnet\Tag\Models\Tag;

class ResourceFactory
{
  public static function createResource($resource)
  {
    $objects = [
      Post::class => [
        'type' => 'post',
        'item' => PostDetailClient::getPostDetail($resource instanceof Post ? $resource : new Post())
      ],
      Page::class => [
        'type' => 'page',
        'item' => new PageResource($resource)
      ],
      ModelsPage::class => [
        'type' => 'about',
        'item' => new PageResource($resource)
      ],
      Service::class => [
        'type' => 'service',
        'item' => new ServiceResource($resource),
      ],
      Tag::class => [
        'type' => 'tag',
        'item' => new TagResource($resource)
      ],
      File::class => [
        'type' => 'file',
        'item' => new FileDetailResource($resource),
      ],
      Admin::class => [
        'type' => 'team',
        'item' => new AdminResource($resource),
      ],
      Category::class => [
        'type' => 'category',
        'item' => CategoryDetailClient::getPostInCategory($resource instanceof Category ? $resource : new Category())
      ],
      PortfolioProject::class => [
        'type' => 'portfolio-project',
        'item' => new PortfolioProjectResource($resource),
      ],
      Product::class => [
        'type' => 'product',
        'item' => new ProductDetailResource($resource),
      ],
    ];
    $type = !empty($resource->name) ? $resource->name : $resource;

    return !empty($objects[get_class($resource)]) ? $objects[get_class($resource)] : new BlogException("Not found the model type $type .");
  }
}
