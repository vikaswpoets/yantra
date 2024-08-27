<?php
//Autoload required classes and files
use System\Theme;

\System\PluginManager::autoloadDir(__DIR__."\Controllers");
\System\PluginManager::autoloadDir(__DIR__."\Models");
\System\PluginManager::autoloadDir(__DIR__."\Services");
\System\PluginManager::autoloadDir(__DIR__."\Api");
//\System\PluginManager::autoloadDir(__DIR__."\Views");
require_once "config.php";
require_once "router.php";

add_filter('get_theme',function ($theme, $theme_name) {
    global $request;
    $rpath = $request->getPath();
    if (is_string($rpath) && str_starts_with($rpath, "/admin")){
        return new Theme("yantraadmin");
    }

    if (!$theme) {
        return new Theme($theme_name);
    }
    return $theme;
},9);