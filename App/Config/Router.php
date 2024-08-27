<?php
global  $router;
$router->addRoute('GET', '/', 'Controllers\\HomeController', 'index');
$router->addRoute('GET', '/about', 'Controllers\\AboutController', 'index');
