<?php
/**
 *Config
 */

use System\{Config, PluginManager, Request, Response, Router};

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * Yantra framework
 */
require 'vendor/autoload.php';
global $router, $request, $response, $env;
$router = new Router();
$request = new Request();
$response = new Response();
$env = new stdClass();
$env->theme = null;
$env->errors = array();

require_once 'System/functions.php';
require_once 'System/core.php';
require_once 'App/Config/Router.php';

Config::load(__DIR__ . '\App\Config\App.php');
PluginManager::loadPlugins(Config::get('plugins'));
PluginManager::activatePlugins();
$router->dispatch($request, $response);
exit();