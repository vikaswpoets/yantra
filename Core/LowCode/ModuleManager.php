<?php

namespace Core\LowCode;

use Exception;

class ModuleManager
{
    //public static array $Modules = [];
    private static string $dir = "App/Core/Modules/";
    private static string $cacheDir = "App/cache/Modules/";

    /**
     * Set the directory for Modules.
     */
    public static function setDir(string $dir): void
    {
        self::$dir = rtrim($dir, '/') . '/';
    }

    /**
     * Set the directory for cache.
     */
    public static function setCacheDir(string $cacheDir): void
    {
        self::$cacheDir = rtrim($cacheDir, '/') . '/';
    }

    /**
     * Register a new module by loading its .ym file, using caching.
     * @throws ModuleNotFoundException
     * @throws Exception
     */
    public static function register(string $name, $dir = null): void
    {
        self::validateName($name);

        $dir = $dir ?? self::$dir;
        $path = $dir . "$name.ym";
        $cachePath = $dir.'cache/' . "$name.cache";

        // Check if module is cached
        if (file_exists($cachePath)) {
            LowCode::$modules[$name] = unserialize(file_get_contents($cachePath));
            return;
        }

        if (!is_dir($dir.'cache/')) {
            // Create the directory and any necessary parent directories
            mkdir($dir.'cache/', 0755, true);
        }

        // Load module content
        if (!file_exists($path)) {
            throw new ModuleNotFoundException("Module file not found: $path");
        }

        $content = file_get_contents($path);
        $module = new LowCodeModule($name,$content);

        // Cache the module
        file_put_contents($cachePath, serialize($module));
        LowCode::$modules[$name] = $module;
    }

    /**
     * Unregister a module.
     * @throws Exception
     */
    public static function unregisterModule(string $name): void
    {
        self::validateName($name);
        unset(LowCode::$modules[$name]);

        $cachePath = self::$cacheDir . "$name.cache";
        if (file_exists($cachePath)) {
            unlink($cachePath);
        }
    }

    /**
     * Get a registered module by name.
     * @throws ModuleNotFoundException
     * @throws Exception
     */
    public static function get(string $name): LowCodeModule
    {
        self::validateName($name);

        if (str_starts_with($name, "module.")) {
            $name = substr($name, strlen("module."));
        }

        if (isset(LowCode::$modules[$name])) {
            return LowCode::$modules[$name];
        }

        throw new ModuleNotFoundException("Module not found: $name");
    }

    /**
     * Render a template from the specified module.
     * @throws ModuleNotFoundException
     * @throws TemplateNotFoundException
     * @throws Exception
     */
/*    public static function render(string $moduleName, string $templateName, array $attributes = []): string
    {
        echo "-1";
        $module = self::get($moduleName);

        return $module->run($templateName, $attributes);
    }*/

    /**
     * Validate module and template names for allowed characters.
     * @throws Exception
     */
    private static function validateName(string $name): void
    {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $name)) {
            throw new Exception("Invalid name: $name. Names can only contain alphanumeric characters, dots, dashes, and underscores.");
        }
    }
}
