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

        $attr = $config['attributes'] ?? [];
        $default = $attr['default'] ?? "";

        $value = "";

        if (isset($attr['main'])) {
            // Check if we are dealing with an object property or method
            if (strpos($attr['main'], '.') !== false) {
                list($objectName, $propertyOrMethod) = explode('.', $attr['main'], 2);
                $object = $this->get($objectName);

                if (is_object($object)) {
                    if (method_exists($object, $propertyOrMethod)) {
                        // Call the method on the object
                        $value = call_user_func_array([$object, $propertyOrMethod], array_values($attr));
                    } elseif (property_exists($object, $propertyOrMethod)) {
                        // Access the property on the object
                        $value = $object->$propertyOrMethod;
                    } else {
                        throw new \Exception("Method or property '{$propertyOrMethod}' does not exist in object '{$objectName}'.");
                    }
                } else {
                    throw new \Exception("Object '{$objectName}' does not exist.");
                }
            } else {
                // Previous functionality to get value from the environment or default
                $value = $this->get($attr['main']) ?? $default;
            }

            // Set the value to another variable if needed
            if (isset($attr['set'])) {
                $this->set($attr['set'], $value);
                return "";
            }
        }

        // Handle different types of values
        if (is_string($value)) {
            return $value;
        } elseif (is_array($value) || is_object($value)) {
            return json_encode($value);
        } elseif (is_bool($value)) {
            return $value ? "true" : "false";
        } elseif (is_numeric($value)) {
            return "$value";
        }

        return "";
    }
}