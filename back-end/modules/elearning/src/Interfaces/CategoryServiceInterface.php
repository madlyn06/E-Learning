<?php

namespace Modules\Elearning\Interfaces;

interface CategoryServiceInterface
{
    public function getCategoryTree();
    public function buildTree(): array;
}
