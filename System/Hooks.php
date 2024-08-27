<?php

namespace System;

class Hooks {
    private static array $actions = [];
    private static array $filters = [];

    public static function add_action($hook, $callback, $priority = 10): void
    {
        self::$actions[$hook][$priority][] = $callback;
    }

    public static function do_action($hook, ...$args): void
    {
        if (isset(self::$actions[$hook])) {
            foreach (self::$actions[$hook] as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    call_user_func_array($callback, ...$args);
                }
            }
        }
    }

    public static function add_filter($hook, $callback, $priority = 10): void
    {
        self::$filters[$hook][$priority][] = $callback;
    }

    /**
     * @param $hook
     * @param $value
     * @return mixed
     */
    public static function apply_filter($hook, $value, ...$args): mixed {
        if (isset(self::$filters[$hook])) {
            foreach (self::$filters[$hook] as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    $value = call_user_func($callback, $value, ...$args);
                }
            }
        }
        return $value;
    }
}
