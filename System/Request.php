<?php

namespace System;

class Request
{
    //public string|false $file_content;
    protected string $basePath;

    private array $attributes;

    public function __construct()
    {
        $this->basePath = $this->getBasePath();
        $this->attributes = array();
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function set($attribute, $value): void
    {
        $this->attributes[$attribute] = $value;
    }

    public function attr($name){
        if(isset($this->attributes[$name]))
            return $this->attributes[$name];
        return null;
    }
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $this->stripBasePath($path);
    }

    protected function stripBasePath($path): string
    {
        if (str_starts_with($path, $this->basePath)) {
            return substr($path, strlen($this->basePath));
        }
        return $path;
    }

    protected function getBasePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('\\', '/', dirname($scriptName));
        if ($basePath === '/') {
            $basePath = '';
        }
        return $basePath;
    }

    public function getQuery(string $key = null, $default = null)
    {
        $query = $_GET;
        if ($key === null) {
            return $query;
        }
        return isset($query[$key]) ? htmlspecialchars($query[$key], ENT_QUOTES, 'UTF-8') : $default;
    }

    private function getValueFromNestedArray(array $data, array $keys, $default)
    {
        // Base case: If no more keys, return the current value or default
        if (empty($keys)) {
            return $default;
        }

        $key = array_shift($keys); // Get the first key

        // If the current key exists and is an array, continue to the next level
        if (isset($data[$key]) && is_array($data[$key])) {
            if (empty($keys)) {
                // If there are no more keys to process, return the current value
                return $data[$key];
            }

            // Recursive case: Key exists and is an array
            return $this->getValueFromNestedArray($data[$key], $keys, $default);
        } elseif (isset($data[$key])) {
            // If the current key exists but is not an array
            if (empty($keys)) {
                // If there are no more keys to process, return the current value
                return $data[$key];
            }
            // If more keys are left to process, but the current data is not an array
            return $default;
        }

        // Key does not exist
        return $default;
    }

    public function input(string $key, $default = null)
    {
        // Get data from $_REQUEST
        $data = $_REQUEST;
        if ($data == null) {
            return $default;
        }
        // Split the key by dot notation
        $keys = explode('.', $key);

        // Call the recursive function to retrieve the value
        return $this->getValueFromNestedArray($data, $keys, $default);
    }

    public function jsonInput(string $key, $default = null)
    {
        // Read raw POST data
        $data = json_decode(file_get_contents('php://input'), true);
        $keys = explode('.', $key);
        if ($data == null) {
            return $default;
        }
        return $this->getValueFromNestedArray($data, $keys, $default);
    }

    public function file(string $key)
    {
        return $_FILES[$key] ?? null;
    }

    public function getHeader(string $name)
    {
        $name = strtoupper(str_replace('-', '_', $name));
        return $_SERVER['HTTP_' . $name] ?? null;
    }

    public function isAjax(): bool
    {
        return $this->getHeader('X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isDelete(): bool
    {
        return $this->getMethod() === 'DELETE';
    }

    public function isPut(): bool
    {
        return $this->getMethod() === 'PUT';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function all(): array
    {
        return $_REQUEST;
    }
    public function has(string $key): bool
    {
        return isset($_REQUEST[$key]);
    }

    public function view(string $viewPath, array $data = []): void
    {
        extract($data);
        include $viewPath;
    }

}

