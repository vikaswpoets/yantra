<?php

namespace Plugins\yantraadmin\Services;

use Exception;
use PDO;
use Plugins\yantraadmin\Models\PermissionModel;
use Plugins\yantraadmin\Models\RoleModel;
use Plugins\yantraadmin\Models\UserMetaModel;
use Plugins\yantraadmin\Models\UserModel;
use System\Model;

class UserService
{
    public function __construct()
    {

    }

    /**
     * @param string $userEmail
     * @param string $username
     * @param string $password
     * @param array $roles
     * @return int
     * @throws Exception
     */
    public function createUser(string $userEmail, string $username, string $password, array $roles = []): int
    {
        $user = new UserModel();

        // Insert user into database
        $userId = $user->create($userEmail, $username, $password);

        if ($userId > 0) {
            // Assign roles and default permissions
            foreach ($roles as $roleId) {
                $this->assignRole($userId, $roleId);

                // Assign default permissions for the role
                $this->assignDefaultPermissions($userId, $roleId);
            }
        }
        return $userId;
    }

    /**
     * @throws Exception
     */
    public function addUser(string $userEmail, string $username, string $password, string $contact, string $first_name, string $last_name, string $display_name, int $user_status, array $roles = []): int
    {
        $user = new UserModel();

        // Insert user into database
        $userId = $user->create($userEmail, $username, $password,$contact,$first_name,$last_name,$display_name,$user_status);

        if ($userId > 0) {
            // Assign roles and default permissions
            foreach ($roles as $roleId) {
                $this->assignRole($userId, $roleId);

                // Assign default permissions for the role
                $this->assignDefaultPermissions($userId, $roleId);
            }
        }
        return $userId;
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $id, string $contact, string $first_name, string $last_name, string $display_name, int $user_status, array $roles = []): int
    {

        $user = new UserModel();

        $userData = ['updated_at' => date('Y-m-d H:i:s')];

        if(!empty($contact))
            $userData['contact_no'] = $contact;
        if(!empty($first_name))
            $userData['first_name'] = $first_name;
        if(!empty($last_name))
            $userData['last_name' ] =  $last_name;
        if(!empty($display_name))
            $userData[ 'display_name' ] =  $display_name;
        if(!empty($user_status))
            $userData[ 'user_status' ] =  $user_status;

        // Insert user into database
        return $user->update($userData);
    }


    /**
     * @param int $userId
     * @param int $roleId
     * @return void
     * @throws Exception
     */
    public function assignDefaultPermissions(int $userId, int $roleId): void
    {
        $m = new Model('ya_role_permissions', 'role_id ');
        try {
            $defaultPermissions = $m->query()
                ->where('role_id', '=', $roleId)
                ->getResults();

            foreach ($defaultPermissions as $permission) {
                $this->assignPermission($userId, $permission['permission_id']);
            }
        } catch (Exception $e) {
            // Handle exception, log error, etc.
            throw new Exception("Failed to assign default permissions: " . $e->getMessage());
        }
    }

    /**
     * @param string $email
     * @return array|false
     * @throws Exception
     */
    public function findByEmail(string $email): array|false
    {
        $user = new UserModel();
        try {
            return $user->query()
                ->where('user_email', '=', $email)
                ->getResult();

        } catch (Exception $e) {
            // Handle exception, log error, etc.
            throw new Exception("Failed to find user by email: " . $e->getMessage());
        }
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws Exception
     */
    public function getPaginatedUsers(int $page = 1, int $perPage = 10): array
    {
        $user = new UserModel();
        $offset = ($page - 1) * $perPage;
        try {
            return $user->query()
                ->orderBy('created_at', 'DESC')
                ->limit($perPage)
                ->offset($offset)
                ->getResults();
        } catch (Exception $e) {
            // Handle exception, log error, etc.
            throw new Exception("Failed to get paginated users: " . $e->getMessage());
        }
    }

    /**
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getRoles(int $userId): array
    {
        $m = new UserModel();
        return $m->select('ya_user_roles')
            ->join('ya_roles', 'ya_user_roles.role_id', '=', 'ya_roles.id')
            ->where('ya_user_roles.user_id', '=', $userId)
            ->getResults();
    }

    /**
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getPermissions(int $userId): array
    {
        $m = new PermissionModel();
        return $m->query()
            ->join('ya_permissions', 'ya_user_permissions.permission_id', '=', 'ya_permissions.id')
            ->where('ya_user_permissions.user_id', '=', $userId)
            ->getResults();
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @return int
     * @throws Exception
     */
    public function assignRole(int $userId, int $roleId): int
    {
        $m = new Model('ya_user_roles', 'id');
        return $m->insert(['user_id' => $userId, 'role_id' => $roleId]);
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @return int
     * @throws Exception
     */
    public function removeRole(int $userId, int $roleId): int
    {
        $m = new Model('ya_user_roles', 'id');
        return $m->query()
            ->where('user_id', '=', $userId)
            ->where('role_id', '=', $roleId)
            ->delete();
    }

    /**
     * @param int $userId
     * @param int $permissionId
     * @return int
     * @throws Exception
     */
    public function assignPermission(int $userId, int $permissionId): int
    {
        $m = new Model('ya_user_permissions', 'user_id');
        return $m->insert(['user_id' => $userId, 'permission_id' => $permissionId]);
    }

    /**
     * @param int $userId
     * @param int $permissionId
     * @return int
     * @throws Exception
     */
    public function removePermission(int $userId, int $permissionId): int
    {
        $m = new Model('ya_user_permissions', 'user_id');
        return $m->query()
            ->where('user_id', '=', $userId)
            ->where('permission_id', '=', $permissionId)
            ->delete();
    }

    /**
     * @param $adminEmail
     * @param $adminUsername
     * @return void
     * @throws Exception
     */
    public function initDefaults($adminEmail, $adminUsername): void
    {
        $adminPassword = 'password123';
        $adminRoleName = 'Administrator';
        $adminPermissions = [
            'manage_users',
            'manage_roles',
            'manage_permissions'
        ];

        $existingUser = $this->findByEmail($adminEmail);
        if ($existingUser)
            return;

        // Initialize Role model
        $roleModel = new RoleModel();

        // Check if administrator role exists, if not create it
        //$existingRole = $roleModel->db->table($roleModel->table)->where('role_name', '=', $adminRoleName)->getResult();
        $existingRole = $roleModel
            ->where('role_name', '=', $adminRoleName)
            ->getResult();

        if (!$existingRole) {
            $roleId = $roleModel->createRole($adminRoleName, "$adminRoleName role");
            $permissionModel = new PermissionModel();

            foreach ($adminPermissions as $permissionName) {
                // Check if permission exists, if not create it
                $existingPermission = $permissionModel->query()
                    ->where('permission_name', '=', $permissionName)
                    ->getResult();

                if (!$existingPermission) {
                    $permissionId = $permissionModel->createPermission($permissionName, ucfirst(str_replace('_', ' ', $permissionName)));
                } else {
                    $permissionId = $existingPermission['id'];
                }
                $roleModel->assignPermission($roleId, $permissionId);
            }
        } else {
            $roleId = $existingRole['id'];
        }

        // Create administrator user and assign administrator role
        $userId = $this->createUser($adminEmail, $adminUsername, $adminPassword, [$roleId]);
        echo "Administrator user created successfully with ID: $userId\n";

    }

    /**
     * @param $draw
     * @param $start
     * @param $length
     * @param $order
     * @param $columns
     * @param $search
     * @return array
     * @throws Exception
     */
    public function getTableRows(array $columns, int $start, int $length, array $order = [], array $search = []): array
    {
        $user = new UserModel();
        return $user->select('ya_users.id as ID, ya_users.user_login as username, ya_users.first_name as first_name, ya_users.user_email as email')
            ->getDataTableResults($columns, $start, $length, $order, $search );
    }

    /**
     * @param int $user_id
     * @return array
     * @throws Exception
     */
    public function getUserDetails(int $user_id): object
    {
        // Initialize the UserModel to fetch user details.
        $userModel = new UserModel();
        $user = $userModel->query()->select("ID, user_login, first_name, last_name, user_email, contact_no, user_status, display_name")
            ->where('ID', '=', $user_id)
            ->getResult(PDO::FETCH_OBJ);

        // Fetch user metadata.
        $metaModel = new UserMetaModel();
        $user->meta = $metaModel->get_all_meta($user_id);

        // Fetch user roles.
        $roleModel = new RoleModel();
        $user->roles = $roleModel->query()
            ->join('ya_user_roles', 'ya_roles.id', '=', 'ya_user_roles.role_id')
            ->where('ya_user_roles.user_id', '=', $user_id)
            ->getResults(PDO::FETCH_OBJ);

        // Fetch user permissions.
        $permissionModel = new PermissionModel();
        $user->permissions = $permissionModel->query()
            ->join('ya_user_permissions', 'ya_permissions.id', '=', 'ya_user_permissions.permission_id')
            ->where('ya_user_permissions.user_id', '=', $user_id)
            ->getResults();

        return $user;
    }

//    /**
//     * @throws Exception
//     */
//    public function login(mixed $username, mixed $password):bool
//    {
//        $m = new UserModel();
//        $user = $m->query()
//            ->where('user_email', '=', $username)
//            ->getResult();
//        return password_verify($password, $user['user_pass']);
//    }
}