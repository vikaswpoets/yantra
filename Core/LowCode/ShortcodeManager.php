<?php

namespace Core\LowCode;

use Exception;

class ShortcodeManager
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
    public static function parse(string $content=null, ?Shortcode $parent=null): string | bool
    {
        if ($content==null)
        {
            return '_____';
        }
        //Find child shortcodes into content
        $shortcodeNames = implode('|', array_map('preg_quote', array_keys(LowCode::$shortcodes)));
        $pattern = self::getPattern($shortcodeNames);
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        $elderSibling= null;

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
            $current =self::get($name,$sections);
            $new_content = $current->parse($innerContent,
                array(
                    'attributes'=>$attributes,
                    'selfClosing'=>$selfClosing,
                    'sections'=>$sections
                ),$parent,$elderSibling);


            if(is_string($new_content)){
                $content = self::replaceFirst($name, $new_content, $content);
            }
            else{
                $content = self::replaceFirst($name, "", $content);
            }

            $elderSibling = $current;

        }
        return $content;
    }

    /**
     * @throws ModuleNotFoundException
     * @throws Exception
     */
    public static function parseArray(string $content, LowCodeTemplate | LowCodeModule | Shortcode $parent = null):array
    {
        if ($content==null)
            return array();

        //Find child shortcodes into content
        $shortcodeNames = implode('|', array_map('preg_quote', array_keys(LowCode::$shortcodes)));
        $pattern = ShortcodeManager::getPattern($shortcodeNames);
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        $output = array();
        foreach ($matches[0] as $index => $fullMatch)
        {
            $name = trim(strtolower($matches[1][$index][0]));
            $selfClosing = false;
            $sections = explode('.',trim(trim(trim($matches[2][$index][0]??'main'),'.')));

            if(!empty($matches[4][$index][0]))
                $selfClosing = true;

            $innerContent = $matches[5][$index][0] ?? '';
            $attributes = ShortcodeManager::parseAttributes(trim($matches[3][$index][0]));
            $current =ShortcodeManager::get($name,$sections);
            if(!$selfClosing){
                $item = $attributes['main']??null;
                if($item!==null)
                    $output[$item] = $current->parse($innerContent, array('attributes'=>$attributes, 'selfClosing'=>$selfClosing, 'sections'=>$sections), $parent);
                else
                    $output[] = $current->parse($innerContent, array('attributes'=>$attributes, 'selfClosing'=>$selfClosing, 'sections'=>$sections), $parent);
            }
            else{
                $output[] = $current->parse($innerContent, array('attributes'=>$attributes, 'selfClosing'=>$selfClosing, 'sections'=>$sections), $parent);
            }
        }
        return $output;
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
        $pattern = self::getPattern($shortcode_name);
        return preg_replace($pattern, $replace, $subject, 1);
    }
    public static function replaceFirstAndRemoveRest(string $shortcode_name, string $replace, string $subject): string
    {
        // Get the pattern for the shortcode
        $pattern = self::getPattern($shortcode_name);

        // Find the first match of the shortcode
        if (preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
            // Get the position of the first match
            $firstMatchPos = (int)$matches[0][1];

            // Truncate the string after the first match
            $subject = substr($subject, 0, $firstMatchPos);

            // Now replace the first occurrence of the shortcode with the replacement string
            $subject.=$replace;
        }

        return $subject;
    }

    /*public static function getSingleShortcodePattern($shortcode): string
    {
        return '/\[(\[?)(' . $shortcode . ')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:((?:(?!\[\/?\2\])[^\[]|(?R))*)(?:\[\/\2\])?)(\]?))/m';
    }*/

    public static function getPattern(string $shortcodeNames=null): string
    {
        //if(isset($shortcodeNames)){
            //return '/(?:\[:|\[)(?:('.$shortcodeNames.')(\.[^\s\]]+)*)([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
            //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
       // }
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]])*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(?:\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(?:\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        // return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\/\]]+)*)([^\/\]]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
        //return '/(?:\[:|\[)(?:([a-z]+)(\.[^\s\]]+)*)([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|:\]|\](?:((?:(?!\[:\/?\1\])[^\[]|(?R))*)(?:\[\/\1\]|\[:\/\1\])?)(\]?))/m';
    }

    /**
     * @throws Exception
     */
    public static function validateName(string $name): string
    {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $name)) {
            throw new Exception("Invalid name: $name. Names can only contain alphanumeric characters, dots, dashes, and underscores.");
        }
        return $name;
    }

}

