<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LApp extends Shortcode
{
    public function __construct()
    {
        parent::__construct("app");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null, LowCodeTemplate | LowCodeModule | Shortcode $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;
        $attributes = $config['attributes'] ?? [];
        $action = $config['sections'][0] ?? '';
        $set = $attributes['set'] ?? null;

        if (class_exists($action)) {
            // Instantiate a class
            $reflectionClass = new \ReflectionClass($action);
            $object = $reflectionClass->newInstanceArgs(array_values($attributes));

            if ($set) {
                $this->set($set, $object);
            }

            return "";
        } elseif (function_exists($action)) {
            // Call a function
            $result = call_user_func_array($action, array_values($attributes));

            if ($set) {
                $this->set($set, $result);
            }

            return $result ?? '';
        }

        throw new \Exception("The class or function '{$action}' does not exist.");
    }
}
