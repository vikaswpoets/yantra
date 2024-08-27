<?php

namespace System;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;
    private array $config;

    /**
     * @throws Exception
     */
    public function __construct(array $config = null)
    {
        $this->config = $config ?? require 'App/Config/db.php';
        if (!$this->config) {
            throw new Exception('Database configuration file not found.');
        }
        $this->connect();
    }
    public static function getInstance(bool $singleton=true): Database
    {
        if ($singleton === false) {
            return new self();
        }
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function prepare($query, $options = []): PDOStatement|false
    {
        return $this->pdo->prepare($query, $options);
    }
    /**
     * @throws Exception
     */
    public function connect(): void
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host={$this->config['host']};dbname={$this->config['database']}",
                    $this->config['username'],
                    $this->config['password']
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception('Connection error: ' . $e->getMessage());
            }
        }
    }
    public function lastInsertId(): string|bool
    {
        return $this->pdo->lastInsertId();
    }

    public function quote($string,$type = PDO::PARAM_STR): bool|string
    {
        return $this->pdo->quote($string,$type);
    }

    public function execute(string $query, array $params = []): bool
    {
        $stmt = $this->prepare($query);
        return $stmt->execute($params);
    }

    public function fetch(string $query, array $params = []): array|false
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    public function fetchAll(string $query, array $params = []): array|false
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}