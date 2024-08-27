<?php
global  $router;

/* Admin Pages */
$router->addRoute('GET', '/admin', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'index');
$router->addRoute('GET', '/admin/test', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'test');
$router->addRoute('GET', '/admin/dashboard', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'dashboard');
$router->addRoute('GET', '/admin/logout', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'logout');
$router->addRoute('GET', '/admin/update', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'update');
$router->addRoute('GET', '/admin/comments', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'comments');
$router->addRoute('GET', '/admin/about', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'about');
$router->addRoute('POST', '/admin/login', 'Plugins\\yantraadmin\\Controllers\\HomeController', 'login');

/* Manage Appearance */
$router->addRoute('GET', '/admin/appearance', 'Plugins\\yantraadmin\\Controllers\\AppearanceController', 'index');

/* Manage Plugins */
$router->addRoute('GET', '/admin/plugins', 'Plugins\\yantraadmin\\Controllers\\PluginsController', 'index');

/* Manage Tools */
$router->addRoute('GET', '/admin/tools', 'Plugins\\yantraadmin\\Controllers\\ToolsController', 'index');

/* Manage Settings */
$router->addRoute('GET', '/admin/settings', 'Plugins\\yantraadmin\\Controllers\\SettingsController', 'index');
$router->addRoute('GET', '/admin/profile', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'profile');

/* Manage UserModel */
$router->addRoute('GET', '/admin/users', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'index');
$router->addRoute('POST', '/admin/user-list', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'user_list');
$router->addRoute('GET', '/admin/users/user', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'user');
$router->addRoute('POST', '/admin/users/user', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'user');

$router->addRoute('GET', '/admin/users/roles', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'roles');
$router->addRoute('GET', '/admin/users/role', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'role');
$router->addRoute('POST', '/admin/users/role', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'role');

$router->addRoute('GET', '/admin/users/permissions', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'permissions');
$router->addRoute('GET', '/admin/users/permission', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'permission');
$router->addRoute('POST', '/admin/users/permission', 'Plugins\\yantraadmin\\Controllers\\UsersController', 'permission');


/* Manage Media */
$router->addRoute('GET', '/admin/media', 'Plugins\\yantraadmin\\Controllers\\MediaController', 'index');
$router->addRoute('GET', '/admin/media/create', 'Plugins\\yantraadmin\\Controllers\\MediaController', 'create');

/* Manage Posts */
$router->addRoute('GET', '/admin/posts', 'Plugins\\yantraadmin\\Controllers\\PostsController', 'index');
$router->addRoute('GET', '/admin/posts/create', 'Plugins\\yantraadmin\\Controllers\\PostsController', 'create');
$router->addRoute('GET', '/admin/posts/categories', 'Plugins\\yantraadmin\\Controllers\\PostsController', 'categories');
$router->addRoute('GET', '/admin/posts/tags', 'Plugins\\yantraadmin\\Controllers\\PostsController', 'tags');

/* Manage Pages */
$router->addRoute('GET', '/admin/pages', 'Plugins\\yantraadmin\\Controllers\\PagesController', 'index');
$router->addRoute('GET', '/admin/pages/create', 'Plugins\\yantraadmin\\Controllers\\PagesController', 'create');


$router->addRoute('POST', '/admin/api/check-session', 'Plugins\\yantraadmin\\Controllers\\SessionController', 'index');
