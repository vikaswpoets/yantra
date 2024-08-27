<?php
namespace System;

use Exception;
use PDO;
use PDOStatement;

/**
 * MasterModel class extends QueryBuilder to execute database queries.
 */
abstract class MasterModel extends QueryBuilder
{
    private Database $db;
    /**
     * @throws Exception
     */
    public function __construct(string $table, string $primaryKey)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->reset();
        $this->connect();
    }

    protected function prepare( $query, $options = []): PDOStatement|false{
        return $this->db->prepare($query,$options);
    }
    public function lastInsertId(): int
    {
        return $this->db->lastInsertId();
    }
    
    // Database Connection Methods
    public function connect($config=null): bool|static
    {
        try {
            if (is_array($config)){
                $this->db = new Database($config);
            }
            else{
                $this->db = Database::getInstance();
            }
            $this->db->connect();
            return $this;
        } catch (Exception $e) {
            echo $e->getMessage();
            do_action('register_log', 'Database Error', $e->getCode(), $e->getMessage());
            return false;
        }
    }

    public function quote($string,$type = PDO::PARAM_STR){
        return $this->db->quote($string,$type);
    }

}