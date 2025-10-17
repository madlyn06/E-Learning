<?php

namespace Newnet\Cms\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\PostRepositoryInterface;
use Newnet\Core\Repositories\BaseRepository;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function create(array $data)
    {
        $this->autoFillFirstCategory($data);

        /** @var Post $model */
        $model = $this->model->fill($data);

        $model->author()->associate(Auth::guard('admin')->user());

        $model->save();

        return $model;
    }

    public function updateById(array $data, $id)
    {
        $this->autoFillFirstCategory($data);

        return parent::updateById($data, $id);
    }

    public function findBySlug($slug)
    {
        return $this->model
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function paginateInCategory($category, $itemPerPage)
    {
        if ($category instanceof Model) {
            $catIds = $category->getDescendantIds();
        } elseif ($category instanceof Collection) {
            $catIds = $category->toArray();
        } else {
            $catIds = (array) $category;
        }

        return $this->model
            ->where('is_active', 1)
            ->whereHas('categories', function ($q) use ($catIds) {
                $q->whereIn('category_id', $catIds);
            })
            ->orderByDesc('id')
            ->paginate($itemPerPage);
    }

    protected function autoFillFirstCategory(array &$data)
    {
        if (empty($data['category_id']) && isset($data['categories'][0])) {
            $data['category_id'] = $data['categories'][0];
        }
    }

    public function paginate($itemOnPage)
    {
        $data = $this->model->query();

        if ($name = request('name')) {
            $data->where(function ($q) use ($name) {
                foreach (explode(' ', $name) as $keyword) {
                    if ($keyword = trim($keyword)) {
                        $q->where('name', 'like', "%{$keyword}%");
                    }
                }
            });
        }

        if ($categories = request('categories', [])) {
            $data->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('category_id', $categories);
            });
        }

        if ($category = request('category', [])) {
            $data->whereHas('categories', function ($q) use ($category) {
                $q->where('category_id', $category);
            });
        }

        return $data
            ->with('comments')
            ->with('ratings')
            ->orderBy('id', 'desc')
            ->paginate($itemOnPage);
    }

    public function lastPost($limit = 10)
    {
        return Post::whereIsActive(1)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public function count()
    {
        return Post::whereIsActive(1)->count();
    }

    public function countInCategory(Category $category)
    {
        $catIds = $category->getDescendantIds();

        return Post::whereIsActive(1)
            ->whereHas('categories', function ($q) use ($catIds) {
                $q->whereIn('category_id', $catIds);
            })
            ->count();
    }

    public function stickyPost($limit)
    {
        return Post::whereIsSticky(1)
            ->whereIsActive(1)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    public function relatedPosts(Post $post, $limit = 10)
    {
        $catIds = $post->categories->pluck('id')->toArray();

        return Post::whereIsActive(1)
            ->whereHas('categories', function ($q) use ($catIds) {
                $q->whereIn('category_id', $catIds);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($limit);
    }
}
