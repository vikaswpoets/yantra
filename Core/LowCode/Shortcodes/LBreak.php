<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LBreak extends Shortcode implements LoopBreak
{
    public static string|null $shortcode_name = "break";
    public function __construct()
    {
        parent::__construct("break");
    }

    public function parse(string $content, array $config, LowCodeTemplate|LowCodeModule|Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $attr = $config['attributes']??[];
        $main = $attr['main']??"";
        if(empty($main)){
            $this->break();
        }
        else if($this->evaluateCondition($main)){
            $this->break();
        }
        return "";
    }

    public function break(): void
    {
        $p = $this->parent;
        while ($p !== null){
            if($p instanceof LoopingStructure)
            {
                break;
            }
            $p = $p->parent;
        }
        $p->setLoopStatus(0);
    }
}