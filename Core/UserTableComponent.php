<?php
namespace Core;

use Core\UIComponent;

class UserTableComponent extends UIComponent
{
    private array $users;

    public function __construct($users = [], $attributes = [], $templatePath = '')
    {
        $this->users = $users;
        parent::__construct('user-table', $attributes, $templatePath);
    }

    /**
     * @throws \Exception
     */
    public function render(): string
    {
        $this->attributes['users'] = $this->users;
        return parent::render();
    }
}