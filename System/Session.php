<?php

namespace System;

class Session
{
    private $lifetime;
    private $path;
    private $domain;
    private $secure;
    private $httpOnly;

    public function __construct(int $lifetime = 3600, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true)
    {
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->startSession();
    }

    private function startSession(): void
    {
        session_set_cookie_params($this->lifetime, $this->path, $this->domain, $this->secure, $this->httpOnly);
        session_start();
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function clear(): void
    {
        $_SESSION = [];
    }

    public function destroy(): void
    {
        $this->clear();
        if (session_id() !== '') {
            session_destroy();
        }
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
    }

    public function regenerateId(bool $deleteOldSession = true): void
    {
        session_regenerate_id($deleteOldSession);
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function setFlash(string $key, $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    public function getFlash(string $key)
    {
        $flash = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $flash;
    }

    public function setCookieParams(int $lifetime, string $path, string $domain, bool $secure, bool $httpOnly): void
    {
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->startSession(); // Restart session with new cookie parameters
    }
}