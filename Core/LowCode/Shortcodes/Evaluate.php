<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;

class Evaluate extends Shortcode
{

    public static string|null $shortcode_name = "eval";
    public function __construct()
    {
        parent::__construct("eval");
    }

    public function parse(string $content, array $config, LowCodeTemplate|LowCodeModule|Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $attr = $config['attributes']??[];
        $statement = $attr['main']??'';

        if(empty($statement))
            return "";
        $value = $this->evaluateCondition($statement);
        echo "<p>--</p>";
        var_dump($value);
        if(isset($attr['set'])){
            $this->set($attr['set'],$value);
            return "";
        }

        return $value;
    }
}