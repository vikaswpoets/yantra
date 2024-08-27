<?php

namespace System;

class PluginManager {
    private static $plugins = [];

    public static function loadPlugins(array $ActivePluginsList): void
    {
        // Example: Load all plugins from Plugins directory
        $pluginDir = __DIR__ . '/../Plugins/';
        $pluginFolders = glob($pluginDir . '*', GLOB_ONLYDIR);

        foreach ($ActivePluginsList as $folder){
            $pluginName = basename($folder);
            if(file_exists($pluginDir.$folder . '/index.php')){
                include_once $pluginDir.$folder . '/index.php';
            }
            $pluginFile = $pluginDir.$folder . '/' . $pluginName . '.php';
            if (file_exists($pluginFile)) {
                include_once $pluginFile;
                $pluginClass = "Plugins\\$pluginName\\$pluginName";
                if (class_exists($pluginClass)) {
                    self::$plugins[] = new $pluginClass();
                } else {
                    echo $pluginFile;
                }
            }
        }
    }

    public static function activatePlugins(): void
    {
        foreach (self::$plugins as $plugin) {
            if (method_exists($plugin, 'activate')) {
                $plugin->activate();
            }
        }
    }

    public static function deactivatePlugins(): void
    {
        foreach (self::$plugins as $plugin) {
            if (method_exists($plugin, 'deactivate')) {
                $plugin->deactivate();
            }
        }
    }

    public static function getPlugins(): array
    {
        return self::$plugins;
    }

    public static function autoloadDir($directory):void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php') {
                require_once $file->getRealPath();
            }
        }
    }
}