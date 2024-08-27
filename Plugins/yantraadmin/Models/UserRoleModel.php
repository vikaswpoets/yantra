<?php

namespace Plugins\yantraadmin\Models;

use System\Model;

class UserRoleModel extends Model{
    public function __construct()
    {
        parent::__construct('ya_user_roles', 'id');
    }

    /**
     * @throws \Exception
     */
    public function assignRole(int $userId, int $roleId): void
    {
        $this->insert(['user_id' => $userId, 'role_id' => $roleId]);
    }
}