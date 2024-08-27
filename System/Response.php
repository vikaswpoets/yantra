<?php

namespace System;

use Core\LowCodeOLD;
use Core\Page;
use Core\UIComponent;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;

class Response extends \Core\Response
{
    protected Page $page;

    public function __construct(array $config = [])
    {
        parent::__construct(200,[]);
        $this->page = new Page();
    }

    #[NoReturn] public function render($attributes=[]): string|false
    {
        global $env;
        $this->page->attributes = array_merge($this->page->attributes,$attributes);
        $env->theme = apply_filter('get_theme', $env->theme, Config::get('theme'));
        $env->theme->render($this->page);
        $this->exit();
    }

    public function meta(string $key,mixed $value = null):mixed
    {
        if($value == null){
            if(isset($this->page->attributes[$key]))
                return $this->page->attributes[$key];
            return false;
        }
        $this->page->attributes[$key] = $value;
        return true;
    }
}