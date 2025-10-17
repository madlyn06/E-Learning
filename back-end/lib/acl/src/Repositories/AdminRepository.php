<?php

namespace Newnet\Acl\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Newnet\Acl\Models\Admin;

class AdminRepository implements AdminRepositoryInterface
{
    /**
     * @var Model|\Eloquent
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $email
     * @return \Illuminate\Database\Eloquent\Builder|Model|object
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function allWithRoles($columns = ['*'], $roleColumns = ['id', 'name'])
    {
        return $this->model
            ->with('roles:' . implode(',', $roleColumns))
            ->get($columns);
    }

    public function paginate($itemPerPage)
    {
        return $this->model->orderBy('id', 'DESC')->paginate($itemPerPage);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function create(array $data)
    {
        $this->hashPassword($data);

        return $this->model->create($data);
    }

    public function createWithRoles(array $data, $roles)
    {
        $this->hashPassword($data);

        $user = $this->model->create($data);

        $user->roles()->sync($roles);

        return $user;
    }

    public function update($id, array $data)
    {
        $this->checkForNewPassword($data);

        $model = $this->model->findOrFail($id);
        $model->update($data);

        return $model;
    }

    public function updateAndSyncRoles($id, array $data, $roles)
    {
        $this->checkForNewPassword($data);

        $user = $this->model->findOrFail($id);
        $user->update($data);

        $user->roles()->sync($roles);

        return $user;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);

        return $model->delete();
    }

    /**
     * Hash the password key
     * @param array $data
     */
    private function hashPassword(array &$data)
    {
        $data['password'] = Hash::make($data['password']);
    }

    /**
     * Check if there is a new password given
     * If not, unset the password field
     * @param array $data
     */
    private function checkForNewPassword(array &$data)
    {
        if (array_key_exists('password', $data) === false) {
            return;
        }

        if ($data['password'] === '' || $data['password'] === null) {
            unset($data['password']);

            return;
        }

        $data['password'] = Hash::make($data['password']);
    }
}
