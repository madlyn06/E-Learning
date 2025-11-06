<?php

namespace Modules\Elearning\Services;

use Modules\Elearning\Interfaces\CategoryServiceInterface;
use Modules\Elearning\Repositories\CategoryRepository;

class CategoryService implements CategoryServiceInterface
{
    public function getCategoryTree()
    {
        return app(CategoryRepository::class)->getTree();
    }

    public function buildTree(): array
    {
        return $this->buildNestedTree($this->getCategoryTree());
    }

    private function buildNestedTree($elements, $parentId = 0): array
    {
        $dataTree = [];
        foreach ($elements as $item) {
            if ($item->parent_id == $parentId) {
                $data = [
                    'label' => $item->name,
                    'key' => $item->id,
                    'url' => $item->url,
                    'icon' => $item->icon,
                ];
                if ($item->hasChildren()) {
                    $data['children'] = $this->buildNestedTree($item->children()->get(), $item->id);
                }
                $dataTree[] = $data;
            }
        }
        return $dataTree;
    }
}
