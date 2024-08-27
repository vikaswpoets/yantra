<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCode;
use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class Get extends Shortcode
{
    public function __construct()
    {
        parent::__construct("get");
    }

    public function parse(string $content, array $config, LowCodeTemplate|LowCodeModule|Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): mixed
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $attr = $config['attributes']??[];
        $default = $attr['default']??"";

        $value = "";
        if(isset($attr['main'])){
            $value = $this->get($attr['main'])??$default;
            if(isset($attr['set'])){
                $this->set($attr['set'],$value);
                return "";
            }
        }
        if(is_string($value)){
            return $value;
        }
        elseif(is_array($value) ||is_object($value)){
            var_dump($attr['main']);
            return json_encode($value);
        }
        elseif(is_bool($value)){
            return $value?"true":"false";
        }
        elseif(is_numeric($value)){
            return "$value";
        }

        return "";
    }
}