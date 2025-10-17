<?php

namespace Newnet\Acl\Repositories;

interface RoleRepositoryInterface
{
    public function create(array $data);

    public function all($columns = ['*']);

    public function find($id, $columns = ['*']);

    public function update($id, array $data);

    public function delete($id);
}
