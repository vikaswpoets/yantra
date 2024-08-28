<?php

namespace Core\LowCode;

use Exception;

abstract class Shortcode
{
    public string $name;
    public static string |null $shortcode_name = null;

    public ?Shortcode $parent = null;
    public ?Shortcode $elderSibling = null;
    public ?Shortcode $youngerSibling = null;
    //public array $children = [];
    //protected string $rawContent; // Property to store the raw content
    public function __construct(string $name)
    {
        $this->name = $name;
    }

   /* public function setRawContent(string $content): void
    {
        $this->rawContent = $content;
    }

    public function getRawContent(): string
    {
        return $this->rawContent;
    }*/

    /**
     * @throws Exception
     */
    public function parse(string $content, array $config, ?Shortcode $parent = null, ?Shortcode $elderSibling=null): mixed
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        //Find child shortcodes into content
        $shortcodeNames = implode('|', array_map('preg_quote', array_keys(LowCode::$shortcodes)));
        $pattern = ShortcodeManager::getPattern($shortcodeNames);

        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $index => $fullMatch)
        {
            $child_name = trim(strtolower($matches[1][$index][0]));
            $child_selfClosing = false;
            $sections = explode('.',trim(trim(trim($matches[2][$index][0]??'main'),'.')));

            //echo json_encode($matches[1]);
            if(!empty($matches[4][$index][0]))
                $child_selfClosing = true;

            $child_content = $matches[5][$index][0] ?? '';
            $child_attributes = ShortcodeManager::parseAttributes(trim($matches[3][$index][0]));
            $new_content = ShortcodeManager::get($child_name,$sections)->parse($child_content,
                array(
                    'parentConfig'=>$config,
                    'attributes'=>$child_attributes,
                    'selfClosing'=>$child_selfClosing,
                    'sections'=>$sections
                ),$this);

            $content =  ShortcodeManager::replaceFirst($child_name, $new_content, $content);
        }
        return $content;
    }

    public function set(string $key, mixed $value): void
    {
        if($this->parent === null)
        {
            $keys = explode('.', $key);
            LowCode::setByPath( $keys, $value);
        }
        else{
            $this->parent->set($key, $value);
        }
    }

    public function get(string $key): mixed
    {
        if($this->parent===null){
            $keys = explode('.', $key);
            return LowCode::getByPath($keys);
        }
        return $this->parent->get($key);
    }

    public function hasVariable(string $key): bool
    {
        if ($this->parent === null) {
            $keys = explode('.', $key);
            return LowCode::hasByPath($keys);
        }
        return $this->parent->hasVariable($key);
    }

    public function removeVariable(string $key): void
    {
        if ($this->parent === null) {
            $keys = explode('.', $key);
            LowCode::removeByPath($keys);
        } else {
            $this->parent->removeVariable($key);
        }
    }

    protected function replaceVariables(string $condition): string
    {
        return preg_replace_callback('/\{([^\{\}]+)\}/', function ($matches) {
            return $this->get($matches[1]) ?? '';
        }, $condition);
    }

    protected function evaluate(string $statement): bool
    {
        echo "<p>-$statement--</p>";
        $output =  preg_replace_callback('/\{\{([^\{\}]*)\}\}/', function ($matches) {
            var_dump($matches);
            return $this->evaluate($matches[1]??'');
        }, $statement);
        echo $output;
        return $output;
    }

    protected function evaluateCondition(string $condition): mixed
    {
        try {
            $condition = $this->replaceVariables($condition);
            return eval("return ($condition);");
        } catch (\Throwable $e) {
            echo ($e->getMessage());
            return false;
        }
    }

}