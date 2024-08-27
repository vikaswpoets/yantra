<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;

class LContinue extends Shortcode implements LoopContinue
{
    public static string|null $shortcode_name = "continue";
    public function __construct()
    {
        parent::__construct("continue");
    }

    public function parse(string $content, array $config, LowCodeTemplate|LowCodeModule|Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $attr = $config['attributes']??[];
        $main = $attr['main']??"";
        if(empty($main)){
            $this->continue();
        }
        else if($this->evaluateCondition($main)){
            $this->continue();
        }
        return "";
    }

    public function continue(): void
    {
        $p = $this->parent;
        while ($p !== null){
            if($p instanceof LoopingStructure)
            {
                break;
            }
            $p = $p->parent;
        }
        $p->setLoopStatus(2);
    }
}