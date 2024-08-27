<?php

namespace Core\LowCode;

use Exception;

class ShortcodeManager_OLD
{

    /**
     * @throws Exception
     */
    public static function register(string $name, string $class): void
    {
        if (is_subclass_of($class, Shortcode::class)) {
            LowCode::$shortcodes[$name] = $class;
        } else {
            throw new Exception("Class must be a subclass of Shortcode");
        }
    }

    /**
     * @throws ModuleNotFoundException
     */
    public static function get($name,$sections)
    {
        if (isset(LowCode::$shortcodes[$name])) {
            $className = LowCode::$shortcodes[$name];
            if (is_subclass_of($className, Shortcode::class))
            {
                if($className == LowCodeModule::class)
                    return ModuleManager::get($sections[0]);
                return new $className($name);
            }
        }
    }

/*
    /**
     * @throws Exception
     */
    /**
     * @throws Exception
     */
    public static function parse(string $content, ?Shortcode $parent=null): string
    {
        //Find child shortcodes into content
        $shortcodeNames = implode('|', array_map('preg_quote', array_keys(LowCode::$shortcodes)));
        $pattern = ShortcodeManager_OLD::getPattern($shortcodeNames);
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $index => $fullMatch) {
            $name = trim(strtolower($matches[1][$index][0]));
            $selfClosing = false;
            $sections = explode('.',trim(trim(trim($matches[2][$index][0]??'main'),'.')));
            //LowCode::print_r($matches);
            //exit();
            if(!empty($matches[4][$index][0]))
                $selfClosing = true;
            $innerContent = $matches[5][$index][0] ?? '';
            $attributes = self::parseAttributes(trim($matches[3][$index][0]));
            $new_content = self::get($name,$sections)->parse($innerContent,
                array(
                    'attributes'=>$attributes,
                    'selfClosing'=>$selfClosing,
                    'sections'=>$sections
                ),$parent);
            //echo $new_content;echo "</br>";
            $content = self::replaceFirst($name, $new_content, $content);
        }

        return $content;
    }

    public static function parseAttributes(string $attributesString): array
    {
        $attributes = [];
        $attributesString = trim($attributesString);
        $pattern = '/(?:(?:([.\S]+)=("[^"]+"|\'[^\']+\'|\{[^}]+\}))|(?:(?:\{[^\}]*\}|[.\S]+)))/';
        //$pattern = '/(?:(?:([.\w]+)=("[^"]+"|\'[^\']+\'|\{[^}]+\}))|(?:(?:"[^"]*"|\'[^\']*\')|(?:\{[^\}]*\}|[.\w]+)))/';
        //$pattern = '/(?:(?:([.\w]+)=("[^"]+"|\'[^\']+\'|\{[^}]+\}))|(?:(?:"[^"]*"|\'[^\']*\')|(?:\{[^\}]*\}|\w+)))/';
        //$pattern = '/(?:(?:(\w+)=("[^"]+"|\'[^\']+\'|\{[^}]+\}))|(?:(?:"[^"]*"|\'[^\']*\')|(?:\{[^\}]*\}|\w+)))/';
        preg_match_all($pattern, $attributesString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (isset($match[1])) {
                $key = $match[1];
                //$value = trim($match[2], '"\'{}');
                $attributes[$key] = trim($match[2],'"\'');
            } else {
                if (!isset($attributes['main'])) {
                    $attributes['main'] = trim($match[0], '"\'');//trim($match[0], '"\'{}');
                }
            }
        }

        return $attributes;
    }
    public static function replaceFirst(string $shortcode_name, string $replace, string $subject): string
    {
        $pattern = ShortcodeManager_OLD::getPattern($shortcode_name);
        //echo $pattern;
        return preg_replace($pattern, $replace, $subject, 1);
    }
    public static function getSingleShortcodePattern($shortcode): string
    {
        return '/\[(\[?)(' . $shortcode . ')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:((?:(?!\[\/?\2\])[^\[]|(?R))*)(?:\[\/\2\])?)(\]?))/m';
    }
    public static function getPattern(string $shortcodeNames=null): string
    {
        if(isset($shortcodeNames)){
           return '/(?:\[:|\[)(?:('.$shortcodeNames.')(\.[^\s\]]+)*)([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        }
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]])*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(?:\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(?:\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
       // return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\/\]]+)*)([^\/\]]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
    }
}

