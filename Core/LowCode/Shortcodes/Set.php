<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCode;
use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\ModuleManager;
use Core\LowCode\ModuleNotFoundException;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class Set extends Shortcode
{
    public function __construct()
    {
        parent::__construct("set");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;
        $attr = $config['attributes']??[];
        $action = $config['sections'][0]?? '';
        $default = $attr['default']??"";
        switch ($action) {
            case 'array':{
                if(!empty($attr['main']))
                {
                    $arr_name = $attr['main'];
                    $arr = ShortcodeManager::parseArray($content,$parent);
                    $this->set($arr_name, $arr);
                }
                else
                {
                    throw new \Exception("Missing array name!");
                }
                break;
            }
            case 'increment':{
                if(!empty($attr['main']))
                {
                    $var = $attr['main'];
                    $x = $this->get($var);
                    if(is_numeric($x)){
                        $inc = $attr['x']??1;
                        $y = ($x + $inc);
                        $this->set($var,$y);
                    }
                }
                else
                {
                    throw new \Exception("Missing array name!");
                }
                break;
            }
            case 'decrement':{
                if(!empty($attr['main']))
                {
                    $var = $attr['main'];
                    $x = $this->get($var);
                    if(is_numeric($x)){
                        $inc = $attr['x']??1;
                        $this->set($var,($x-$inc));
                    }
                }
                else
                {
                    throw new \Exception("Missing array name!");
                }
                break;
            }
            default:{
                if(!empty($attr)){
                    foreach ($attr as $key=>$value){
                        if($value == null){
                            $value = $default;
                            //$value = $this->replaceVariables($value);
                            $value = $this->evaluateCondition($value);
                            $this->set($key, $value );
                        }
                        else if($key == 'main'){
                            $value =  $content;
                            //$value = $this->replaceVariables($value);
                            $value = $this->evaluateCondition($value);
                            $this->set($key, $value );
                        }
                        else{
                            $this->set($key, ShortcodeManager::parse($this->evaluateCondition($value),$parent));
                        }
                    }
                }
                break;
            }
        }

        return "";
    }

    /**
     * @throws ModuleNotFoundException
     * @throws \Exception
     */

}