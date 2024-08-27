<?php

namespace Core;

use System\View;

class Page
{
    public string $layout;
    public string $slug;
    public array $attributes;

    public function __construct()
    {
        $this->layout = "";
        $this->attributes = ['content'=>""];
    }

    public function set($attribute, $value): void
    {
        $this->attributes[$attribute] = $value;
    }
}