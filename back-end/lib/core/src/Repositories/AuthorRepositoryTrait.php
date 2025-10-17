<?php

namespace Newnet\Core\Repositories;

use Illuminate\Support\Facades\Auth;

trait AuthorRepositoryTrait
{
    public function createWithAuthor(array $data)
    {
        $model = $this->model->fill($data);

        $model->author()->associate(Auth::guard('admin')->user());

        $model->save();

        return $model;
    }
}
