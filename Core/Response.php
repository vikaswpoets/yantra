<?php
namespace Core;
use Exception;
use JetBrains\PhpStorm\NoReturn;

abstract class Response
{
    protected array $headers;
    protected int $statusCode;

    public function __construct($statusCode, $defaultHeaders = [])
    {
        $this->headers=$defaultHeaders??[];
        $this->statusCode = $statusCode??200;
    }
    public function __set(string $name, mixed $value): void
    {
        if ($name === 'statusCode') $this->statusCode = $value;
    }
    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }
    #[NoReturn] public function exit(string $message=""): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        if(count_chars($message)>0){
            echo $message;
        }
        exit();
    }
    #[NoReturn] public function redirect(string $url,$code=302): void
    {
        $this->statusCode = $code;
        $this->setHeader('Location', $url);
        $this->exit();
    }
    public function setContentType(string $type): void
    {
        $this->setHeader('Content-Type', $type);
    }
    public function setCharset(string $charset): void
    {
        $this->setHeader('Content-Type', 'text/html; charset=' . $charset);
    }
    public function setCache(int $seconds): void
    {
        $this->setHeader('Cache-Control', 'max-age=' . $seconds);
    }
    public function setCookie(string $name, string $value, int $expire = 0,string $path = '',string $domain = '',bool $secure = false,bool $httponly = false ): void
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * @throws Exception
     */
    public function download(string $filePath, string $filename = null): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found.");
        }
        $filename = $filename ?? basename($filePath);
        $this->setHeader('Content-Type', 'application/octet-stream');
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $msg = file_get_contents($filePath);
        $this->exit($msg);
    }
    public function sendFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found.");
        }
        $this->setHeader('Content-Type', mime_content_type($filePath));
        $this->setHeader('Content-Length', filesize($filePath));
        $msg =  file_get_contents($filePath);
        $this->exit($msg);
    }

    #[NoReturn] public function json($data,$statusCode=200): void
    {
        $this->statusCode = $statusCode;
        $this->setHeader('Content-Type', 'application/json');
        $msg =  json_encode($data);
        $this->exit($msg);
    }

    #[NoReturn] public function not_found(): void
    {
        $this->statusCode=404;
        $this->exit("Error 404:Page not found");
    }
}