<?php

namespace Newnet\Acl\Repositories;

interface AdminRepositoryInterface
{
    public function findByEmail($email);

    public function create(array $data);

    public function createWithRoles(array $data, $roles);

    public function all($columns = ['*']);

    public function allWithRoles($columns = ['*'], $roleColumns = ['*']);

    public function paginate($itemPerPage);

    public function find($id, $columns = ['*']);

    public function update($id, array $data);

    public function updateAndSyncRoles($id, array $data, $roles);

    public function delete($id);
}
