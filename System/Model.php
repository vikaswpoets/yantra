<?php

namespace System;

use Exception;
use PDO;
use PDOStatement;

class Model extends MasterModel
{

    public function __construct(string $table, string $primaryKey = null)
    {
        parent::__construct($table,$primaryKey);
        $this->connect();
    }

    /**
     * Get a record by its primary key.
     *
     * @throws Exception
     */
    public function get(int|string $id): array|bool
    {
        $this->query()->where($this->primaryKey, '=', $id);
        return $this->getResult();
    }

    /**
     * Get a record by its primary key and return as associative array.
     *
     * @throws Exception
     */
    public function getObject(int|string $id): object
    {
        if ($this->primaryKey === null) {
            throw new Exception("Primary key not defined");
        }
        $this->query()->where($this->primaryKey, '=', $id);
        return $this->getResult(PDO::FETCH_OBJ);
    }

    /**
     * Insert a new record.
     *
     * @throws Exception
     */
    public function insert(array $data = null): self|int
    {
        if ($data === null) {
            return $this->_query('insert')
                ->executeQuery()
                ->rowCount();;
        }
        $count = $this->query('insert')
            ->data($data)
            ->executeQuery()->rowCount();
        if ($count > 0) {
            return $this->lastInsertId();
        } else {
            return 0;
        }
    }

    /**
     * Update an existing record.
     *
     * @throws Exception
     */
    public function update(array $data = null): self|int
    {
        if ($data === null) {
            return $this->_query('update')->executeQuery()->rowCount();
        }
        return $this->query('update')
            ->data($data)
            ->executeQuery()->rowCount();
    }

    /**
     * Save a record (insert or update).
     *
     * @throws Exception
     */
    public function save(array $data, array $updateColumns): int
    {
        return $this->query('insert')
            ->data($data)
            ->onDuplicateKeyUpdate($updateColumns)
            ->executeQuery()->rowCount();
    }

    /**
     * Insert multiple records in one query.
     *
     * @throws Exception
     */
    public function batchInsert(array $data, bool $ignore = true): self|int
    {
        if ($data === null) {
            $this->query('batchInsert');
            $this->ignore($ignore);
            return $this;
        }
        return $this->query('batchInsert')
            ->data($data)
            ->executeQuery()->rowCount();
    }

    /**
     * Delete a record by its primary key.
     *
     * @throws Exception
     */
    public function delete(int|string $id = null): self|int
    {
        if ($id === null) {
            return $this->_query('delete')->executeQuery()->rowCount();
        }
        return $this->query('delete')
            ->where($this->primaryKey, "=", $id)
            ->executeQuery()->rowCount();
    }

    /**
     * Soft delete a record by its primary key.
     *
     * @throws Exception
     */
    public function softDelete(int|string $id = null): self|int
    {
        if ($id === null) {
            return $this->_query('soft_delete')->executeQuery()->rowCount();
        }
        return $this->query('soft_delete')
            ->where($this->primaryKey, "=", $id)
            ->executeQuery()->rowCount();
    }


    /**
     * @throws Exception
     */
    public function getDataTableResults(array $columns, int $start, int $length, array $order = [], array $search = []): array
    {
        $query = $this;

        // Handling search filters
        if (!empty($search)) {
            foreach ($search as $column => $value) {
                if ($value !== '') {
                    $query->where($columns[$column], 'LIKE', '%' . $value . '%');
                }
            }
        }

        // Handling ordering
        if (!empty($order)) {
            foreach ($order as $column => $direction) {
                $query->orderBy($columns[$column], $direction);
            }
        }

        // Handling pagination
        $query->limit($length)->offset($start);

        // Execute the query and return the results
        $results = $query->executeQuery()->fetchAll(PDO::FETCH_ASSOC);

        // Count total records
        $totalRecords = $this->query()->select('COUNT(*) as total')->executeQuery()->fetchColumn();

        return [
            'records' => $results,
            'totalRecords' => $totalRecords
        ];
    }



}