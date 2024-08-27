<?php
namespace Core;

use Core\UIComponent;

class UserManagementComponent extends UIComponent
{
    public function __construct(array $users, $attributes = [])
    {
        parent::__construct('user-management', $attributes);

        $userTableComponent = new UserTableComponent($users);
        $this->addChildComponent('userTable', $userTableComponent);
    }
}