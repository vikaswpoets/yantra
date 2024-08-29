<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;
use mysqli;

class LMysqli extends Shortcode
{
    protected $connection;

    public function __construct()
    {
        parent::__construct("mysqli");
        // Initialize MySQLi connection (Replace with your DB credentials)
        $this->connection = new mysqli('localhost', 'username', 'password', 'database');

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;
        $attr = $config['attributes'] ?? [];
        $action = $config['sections'][0] ?? '';
        $output = '';

        switch ($action) {
            case 'select':
                $output = $this->select($attr['query'], $attr['set'] ?? null);
                break;

            case 'insert':
                $output = $this->insert($attr['query'], $attr['set'] ?? null);
                break;

            case 'update':
                $output = $this->update($attr['query'], $attr['set'] ?? null);
                break;

            case 'delete':
                $output = $this->delete($attr['query'], $attr['set'] ?? null);
                break;

            default:
                $output = "Invalid MySQL operation.";
                break;
        }

        return $output;
    }

    protected function select(string $query, ?string $set = null): string
    {
        $result = $this->connection->query($query);

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);

            if ($set) {
                $this->set($set, $data);
                return "";
            }
            return json_encode($data);
        }

        return "No results found.";
    }

    protected function insert(string $query, ?string $set = null): string
    {
        if ($this->connection->query($query) === TRUE) {
            $insertId = $this->connection->insert_id;

            if ($set) {
                $this->set($set, $insertId);
                return "";
            }

            return "New record created successfully. Insert ID: $insertId";
        }

        return "Error: " . $this->connection->error;
    }

    protected function update(string $query, ?string $set = null): string
    {
        if ($this->connection->query($query) === TRUE) {
            $affectedRows = $this->connection->affected_rows;

            if ($set) {
                $this->set($set, $affectedRows);
                return "";
            }

            return "$affectedRows record(s) updated successfully.";
        }

        return "Error: " . $this->connection->error;
    }

    protected function delete(string $query, ?string $set = null): string
    {
        if ($this->connection->query($query) === TRUE) {
            $affectedRows = $this->connection->affected_rows;

            if ($set) {
                $this->set($set, $affectedRows);
                return "";
            }

            return "$affectedRows record(s) deleted successfully.";
        }

        return "Error: " . $this->connection->error;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
