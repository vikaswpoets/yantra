<?php

namespace System;

class Router
{
    protected array $routes = [];
    protected array $fileRoutes = [];

    public function addRoute($method, $path, $controller, $action): void
    {
        $pattern = $this->convertPathToPattern($path);

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function addFileRoute($method, $path, $filepath, $controller = null, $action = null): void
    {
        $pattern = $this->convertPathToPattern($path);

        $this->fileRoutes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'filepath' => $filepath,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    protected function convertPathToPattern($path): string
    {
        $pattern = preg_replace(pattern: '/\{([a-zA-Z0-9_]+)\}/', replacement: '(?P<$1>[a-zA-Z0-9_]+)', subject: $path);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    public function dispatch(Request $request, Response $response)
    {
        foreach ($this->routes as $route) {
            if ($request->getMethod() == $route['method'] && preg_match($route['pattern'], $request->getPath(), $matches)) {
                return $this->invokeController($route['controller'], $route['action'], $request, $response, $matches);
            }
        }

        foreach ($this->fileRoutes as $route) {
            if ($request->getMethod() == $route['method'] && preg_match($route['pattern'], $request->getPath(), $matches)) {
                if ($route['controller'] && $route['action']) {
                    if (file_exists($route['filepath'])) {
                        $fileContent = file_get_contents($route['filepath']);
                        $request->set('file_contents',$fileContent);
                        return $this->invokeController($route['controller'], $route['action'], $request, $response, $matches);
                    } else {
                        $response->not_found();
                    }
                } else {
                    if (file_exists($route['filepath'])) {
                        $fileContent = (file_get_contents($route['filepath']));
                        $response->exit($fileContent);
                    } else {
                        $response->not_found();
                    }
                }
            }
        }

        $response->not_found();
    }

    protected function invokeController($controller, $action, Request $request, Response $response, $matches)
    {
        $controller = new $controller();
        $params = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return $controller->$action($request, $response, $params);
    }
}

/*
class Router
{
    protected array $routes = [];

    public function addRoute($method, $path, $controller, $action): void
    {
        // Convert the path pattern to a regular expression
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch(Request $request, Response $response)
    {
        foreach ($this->routes as $route) {
            if ($request->getMethod() == $route['method']) {
                if (preg_match($route['pattern'], $request->getPath(), $matches)) {
                    $controller = new $route['controller']();
                    $action = $route['action'];

                    // Extract parameters from the matches
                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }

                    return $controller->$action($request, $response, $params);
                }
            }
        }
        $response->not_found();
    }
} */