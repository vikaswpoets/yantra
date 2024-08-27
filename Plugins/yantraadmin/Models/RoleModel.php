<?php

namespace Plugins\yantraadmin\Models;

use System\Model;
use Exception;

class RoleModel extends Model
{
    public function __construct()
    {
        parent::__construct('ya_roles', 'id');
    }

    /**
     * Create a new role with validation.
     *
     * @param string $roleName
     * @param string $roleDescription
     * @param array $permissions
     * @return mixed
     * @throws Exception
     */
    public function createRole(string $roleName, string $roleDescription = '', array $permissions = []): mixed
    {
        // Validate required fields
        if (empty($roleName)) {
            throw new Exception("Role name is required.");
        }

        // Prepare role data
        $roleData = [
            'role_name' => $roleName,
            'role_description' => $roleDescription,
        ];

        try {
            // Insert role into database
            $roleId = $this->insert($roleData);
            // Assign permissions to the role
            foreach ($permissions as $permissionId) {
                $this->assignPermission($roleId, $permissionId);
            }

            return $roleId;
        } catch (Exception $e) {
            // Handle exception, log error, etc.
            throw new Exception("Failed to create role: " . $e->getMessage());
        }
    }

    /**
     * Get permissions assigned to a role.
     *
     * @param int $roleId
     * @return array
     * @throws Exception
     */
    public function getPermissions(int $roleId): array
    {
        $pm = new Model('ya_role_permissions');
        return $pm->query()
            ->join('ya_permissions', 'ya_role_permissions.permission_id', '=', 'ya_permissions.id')
            ->where('ya_role_permissions.role_id', '=', $roleId)
            ->getResults();
    }

    /**
     * Assign a permission to a role.
     *
     * @param int $roleId
     * @param int $permissionId
     * @return int
     * @throws Exception
     */
    public function assignPermission(int $roleId, int $permissionId):int
    {
        $pm = new Model('ya_role_permissions');
        return $pm->insert(['role_id' => $roleId, 'permission_id' => $permissionId]);
    }

    /**
     * Remove a permission from a role.
     *
     * @param int $roleId
     * @param int $permissionId
     * @return mixed
     * @throws Exception
     */
    public function removePermission(int $roleId, int $permissionId): mixed
    {
        return (new Model('ya_role_permissions'))
            ->where('role_id', '=', $roleId)
            ->where('permission_id', '=', $permissionId)
            ->delete();
    }

    function createUser($username)
    {
        "INSERT INTO user_table VALUES ($username,,,,,,)";
        //connect to db
        // executer query
        // reat result

    }
}
