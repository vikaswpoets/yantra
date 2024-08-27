<?php

namespace Plugins\yantraadmin\Models;

use Exception;
use System\Model;

class UserPermissionModel extends Model{
    public function __construct()
    {
        parent::__construct('ya_user_permissions', 'user_id');
    }

    /**
     * @throws Exception
     */
    public function assignPermission(int $userId, int $permissionId): mixed
    {
        return $this->insert(['user_id' => $userId, 'permission_id' => $permissionId]);
    }

    /**
     * @throws Exception
     */
    public function assignDefaultPermissions(int $userId, int $roleId): void
    {
        $permission_m = new PermissionModel();

        $role_permissions =$permission_m->getRolePermissions($roleId);

        foreach ($role_permissions as $permission) {
            $this->assignPermission($userId, $permission['permission_id']);
        }

    }
}