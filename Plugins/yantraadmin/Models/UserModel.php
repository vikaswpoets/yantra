<?php

namespace Plugins\yantraadmin\Models;

use System\Model;
use Exception;

class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct('ya_users', 'ID');
    }

    /**
     * @throws Exception
     */
    public function create(string $userEmail, string $username, string $password,string $contact="", string $first_name="",string $last_name="",string $display_name="",int $user_status=0 ): int
    {
        // Validate required fields
        if (empty($userEmail)) {
            throw new Exception("Email is required to add new User.");
        }

        if (empty($username)) {
            throw new Exception("Username is required to add new User.");
        }

        if (empty($password)) {
            throw new Exception("Password is required to add new User.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare user data
        $userData = [
            'user_email' => $userEmail,
            'user_login' => $username,
            'user_pass' => $hashedPassword,
            'contact_no' => $contact,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => $display_name,
            'user_status' => $user_status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            return $this->insert($userData);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

}

