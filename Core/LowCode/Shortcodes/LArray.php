<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCode;
use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\ModuleNotFoundException;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LArray extends Shortcode
{
    public static string|null $shortcode_name = "array";
    public function __construct()
    {
        parent::__construct("array");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;
        $attr = $config['attributes']??[];

        if(!empty($attr['main']))
        {
            $arr_name = $attr['main'];
            $arr = ShortcodeManager::parseArray($content,$parent);
            $this->set($arr_name, $arr);
            return "";
        }
        else
        {
            throw new \Exception("Missing array name!");
        }
    }

}