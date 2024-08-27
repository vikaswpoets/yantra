<?php

namespace System;

class Cookie
{
    private $path;
    private $domain;
    private $secure;
    private $httpOnly;

    public function __construct(string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true)
    {
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    public function set(string $name, $value, int $expire = 3600): bool
    {
        $expire = time() + $expire;
        return setcookie($name, $value, $expire, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    public function get(string $name)
    {
        return $_COOKIE[$name] ?? null;
    }

    public static function has(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public function delete(string $name): bool
    {
        if ($this->has($name)) {
            return setcookie($name, '', time() - 3600, $this->path, $this->domain, $this->secure, $this->httpOnly);
        }
        return false;
    }

    public function setParams(string $path, string $domain, bool $secure, bool $httpOnly): void
    {
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }
}