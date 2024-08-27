<?php

use System\Config;
use System\Hooks;
use System\Theme;

function add_action($hook, $callback, $priority = 10): void {
    Hooks::add_action($hook,$callback,$priority);
}

function do_action($hook, ...$args): void {
    Hooks::do_action($hook,$args);
}
function add_block($block_name, $callback, $priority = 10): void {
    Hooks::add_action("yfb_".$block_name,$callback,$priority);
}

function do_block($block_name, $default, $append=false): void {
    Hooks::do_action("yfb_".$block_name,[$default,$append]);
}

function add_filter($hook, $callback, $priority = 10): void {
    Hooks::add_filter($hook, $callback, $priority);
}

function apply_filter($hook,$value, ...$args): mixed
{
    return Hooks::apply_filter($hook, $value,...$args);
}
//
//function apply_layout($name='',$view='index',$data = []): void
//{
//    global $env;
//    $env->theme->apply_layout($name, $view, $data);
//}

function the_section($template, $file, $data = []): void
{
    global $env;
    $env->theme->load($template, $file, $data);
}


function the_content(): void
{
    global $env;
    require "{$env->page_view}.php";
}

//Warning: Undefined property: stdClass::$page_layout in C:\xampp\htdocs\Yantra\System\Template.php on line 68
//function get_view($view, $data): void
//{
//    extract($data);
//    require "App/Views/{$view}.php";
//}

function site_url(string $append=""): string
{
    // Check if the connection is using HTTPS
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the host
    $host = $_SERVER['HTTP_HOST'];

    // Get the script name
    //$script = $_SERVER['SCRIPT_NAME'];

    // Combine them to get the full URL
    $url = $protocol . $host."/yantra";
    return $append == null || $append == "" ? $url : $url . "/" . $append;
}

function get_current_url(): string
{
    // Check if the connection is using HTTPS
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the host
    $host = $_SERVER['HTTP_HOST'];

    // Get the request URI (including the path and query string)
    $requestUri = $_SERVER['REQUEST_URI'];

    // Combine them to get the full URL
    return $protocol . $host . $requestUri;
}

function theme_url()
{
    global $env;
    return $env->theme->url;
}
function load_html($file, $data = [])
{
    global $env;
    return $env->theme->load_html($file, $data);
}

add_filter('get_theme',function ($theme, $name) {
    global $env;
    if (!isset($env->theme)) {
        if (!$theme) {
            return new Theme($name);
        }
        return $theme;
    }

    return $env->theme;
},10);
