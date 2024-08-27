<?php

namespace System;

use Exception;
use PDO;
use PDOStatement;

/**
 * Abstract class for building SQL queries.
 */
abstract class QueryBuilder
{
    private string $queryType;
    protected string $table;
    protected string $primaryKey;
    private string|array $select = '*';
    private array $selectRaw = [];
    private array $where = [];
    private array $orderBy = [];
    protected array $groupBy = [];
    private ?int $limit = null;
    private ?int $offset = 0;
    private array $joins = [];
    private array $withClauses = [];
    private array $insertData = [];
    private array $updateData = [];
    private string $softDeleteColumn = 'deleted';
    private array $batchInsertData = [];
    private array $onDuplicateKeyUpdate = [];
    private bool $ignore = false;
    private bool $distinct = false;

    protected array $bindings = [];


    /**
     * Resets the query builder state.
     */
    public function reset(string $type = "select"): static
    {
        $this->select = '*';
        $this->queryType = $type;
        $this->where = [];
        $this->orderBy = [];
        $this->limit = null;
        $this->joins = [];
        $this->withClauses = [];
        $this->updateData = [];
        $this->softDeleteColumn = 'deleted';
        $this->batchInsertData = [];
        $this->offset = 0;
        $this->ignore = false;
        $this->bindings = [];
        $this->distinct = false;
        return $this;
    }

    /**
     * Sets the query type and resets the builder state.
     *
     * @param string $type The query type (select, insert, etc.)
     * @return $this
     */
    public function query(string $type = "select"): self
    {
        $this->reset();
        $this->queryType = $type;
        return $this;
    }

    public function _query(string $type = "select"): self
    {
        $this->queryType = $type;
        return $this;
    }

    /**
     * Adds a raw SELECT part to the query.
     *
     * @param string $expression The raw SQL expression.
     * @return $this
     */
    public function selectRaw(string $expression): self
    {
        $this->selectRaw[] = $expression;
        return $this;
    }
    /**
     * Sets the columns to select.
     *
     * @param string|array $columns The columns to select.
     * @return $this
     */
    public function select(string|array $columns): self
    {
        $this->select = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    /**
     * Sets the DISTINCT flag for the SELECT query.
     *
     * @return $this
     */
    public function distinct(): self
    {
        $this->distinct = true;
        return $this;
    }

    /**
     * Sets data for insert, update, or batchInsert queries.
     *
     * @param string $value The value to set.
     * @return $this
     */
    public function set(string $column, string $value): self
    {
        if ($this->queryType == 'update') {
            $this->updateData[$column] = $value;
        } elseif ($this->queryType == 'insert') {
            $this->insertData[$column] = $value;;
        }
        return $this;
    }

    public function setRecord(array $record): static
    {
        if ($this->queryType == 'batchInsert') {
            $this->batchInsertData[]  = $record;
        }
        else if ($this->queryType == 'insert') {
            $this->insertData[]  = $record;
        }
        return $this;
    }

    /**
     * Sets the data for insert, update, or batchInsert queries.
     *
     * @param array $data The data to set.
     * @return $this
     */
    public function data(array $data): self
    {
        if ($this->queryType == 'update') {
            $this->updateData = $data;
        } elseif ($this->queryType == 'insert') {
            $this->insertData  = $data;
        } elseif ($this->queryType == 'batchInsert') {
            $this->batchInsertData  = $data;
        }
        return $this;
    }

    /**
     * Sets the ignore flag for insert or batchInsert queries.
     *
     * @param bool $ignore Whether to ignore conflicts.
     * @return $this
     */
    public function ignore(bool $ignore = true): self
    {
        $this->ignore = $ignore;
        return $this;
    }

    /**
     * Sets the on duplicate key update data for insert queries.
     *
     * @param array $data The data to update on duplicate key.
     * @return $this
     */
    public function onDuplicateKeyUpdate(array $data): self
    {
        $this->onDuplicateKeyUpdate = $data;
        return $this;
    }

    /**
     * Adds a WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string|null $operator The comparison operator.
     * @param mixed $value The value to compare against.
     * @param string $logic The logical operator (AND / OR).
     * @return $this
     */
    public function where(string $column , string $operator = null, mixed $value = null, string $logic = 'AND'): self
    {
        if($operator==null){
            $this->where[] = compact('column',  'logic');
        }
        else{
            $this->where[] = compact('column', 'operator', 'value', 'logic');
        }
        return $this;
    }

    /**
     * Adds an OR WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The comparison operator.
     * @param mixed $value The value to compare against.
     * @return static
     */
    public function orWhere(string $column, string $operator, mixed $value): static
    {
        $this->where[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'logic' => 'OR'
        ];
        return $this;
    }

    /**
     * Adds a WHERE LIKE condition to the query.
     *
     * @param string $column The column name.
     * @param string $value The value to search for.
     * @param string $logic Logical operator AND / OR
     * @return static
     */
    public function whereLike(string $column, string $value, string $logic = 'AND'): static
    {
        $this->where[] = [
            'column' => $column,
            'operator' => 'LIKE',
            'value' => $value,
            'logic' => $logic
        ];
        return $this;
    }
    /**
     * Sets the ORDER BY clause in the query.
     *
     * @param string $column The column to sort by.
     * @param string $direction The sort direction (ASC or DESC).
     * @return $this
     */
   /* public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBy = compact('column', 'direction');
        return $this;
    }*/

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function groupBy(...$columns): self
    {
        $this->groupBy = array_merge($this->groupBy, $columns);
        return $this;
    }

    /**
     * Sets the LIMIT clause in the query.
     *
     * @param int $limit The maximum number of rows to return.
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Sets the OFFSET clause in the query.
     *
     * @param int $offset The number of rows to skip.
     * @return $this
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Adds a JOIN clause to the query.
     *
     * @param string $table The table to join.
     * @param string $first The first column in the join condition.
     * @param string $operator The comparison operator.
     * @param string $second The second column in the join condition.
     * @param string $type The type of join (INNER, LEFT, RIGHT, etc.).
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = compact('table', 'first', 'operator', 'second', 'type');
        return $this;
    }

    /**
     * Adds a WITH clause (Common Table Expression) to the query.
     *
     * @param string $cteName The name of the Common Table Expression.
     * @param string $query The query defining the CTE.
     * @return $this
     */
    public function with(string $cteName, string $query): self
    {
        $this->withClauses[$cteName] = $query;
        return $this;
    }

    /**
     * Builds and returns the SQL query string.
     *
     * @return string The generated SQL query.
     * @throws Exception If the query type is unsupported.
     */
    public function build(): string
    {
        switch ($this->queryType) {
            case 'select':
                return $this->buildSelectQuery();
            case 'insert':
                return $this->buildInsertQuery();
            case 'batchInsert':
                return $this->buildBatchInsertQuery();
            case 'update':
                return $this->buildUpdateQuery();
            case 'delete':
                return $this->buildDeleteQuery();
            case 'soft_delete':
                return $this->buildSoftDeleteQuery();
            default:
                throw new Exception("Unsupported query type '{$this->queryType}'");
        }
    }

    /**
     * Builds the WHERE clause.
     *
     * @return string The generated WHERE clause.
     */
   /* private function buildWhereClause(): string
    {
        $query = '';

        if (!empty($this->where)) {
            $query .= ' WHERE ';
            foreach ($this->where as $index => $condition) {
                if ($index > 0) {
                    $query .= " {$condition['logic']} ";
                }
                if(isset($condition['value']) && isset($condition['operator']))
                    $query .= "{$condition['column']} {$condition['operator']} :where_{$index}";
                else
                    $query .= " ".$condition['column'];
            }
        }

        return $query;
    }*/
    private function buildWhereClause(): string
    {
        if (empty($this->where)) {
            return '';
        }

        $clauses = [];
        foreach ($this->where as $index => $condition) {
            $logic = $index > 0 ? " {$condition['logic']} " : '';
            if (isset($condition['operator']) && isset($condition['value'])) {
                $clauses[] = "{$logic}{$condition['column']} {$condition['operator']} :where_{$index}";
                $this->bindings[":where_{$index}"] = $condition['value'];
            } else {
                $clauses[] = "{$logic}{$condition['column']}";
            }
        }

        return ' WHERE ' . implode(' ', $clauses);
    }

    /**
     * Builds the WITH clause.
     *
     * @return string The generated WITH clause.
     */
    private function buildWithClause(): string
    {
        $query = '';

        if (!empty($this->withClauses)) {
            $query .= 'WITH ';
            $cteQueries = [];
            foreach ($this->withClauses as $cteName => $cteQuery) {
                $cteQueries[] = "{$cteName} AS ({$cteQuery})";
            }
            $query .= implode(', ', $cteQueries) . ' ';
        }

        return $query;
    }



    /**
     * Builds the SELECT part of the query.
     *
     * @return string The generated SELECT part.
     */
    protected function buildSelect(): string
    {
        $selectParts = is_array($this->select) ? $this->select : [$this->select];
        $selectParts = array_merge($selectParts, $this->selectRaw);
        return implode(', ', $selectParts);
    }

    /**
     * Builds a SELECT query string.
     *
     * @return string The generated SELECT query.
     */
    protected function buildSelectQuery(): string
    {
        $query = $this->buildWithClause();
        $distinct = $this->distinct ? 'DISTINCT ' : '';
        $query .= "SELECT $distinct" . $this->buildSelect() . " FROM {$this->table}";

        //$query .= "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $query .= " {$join['type']} JOIN {$join['table']} ON {$join['first']} {$join['operator']} {$join['second']}";
            }
        }

        $query .= $this->buildWhereClause();

        if (!empty($this->groupBy)) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (!empty($this->orderBy)) {
            $query .= " ORDER BY " . implode(', ', $this->orderBy);
        }

/*        if (!empty($this->orderBy)) {
            $query .= " ORDER BY {$this->orderBy['column']} {$this->orderBy['direction']}";
        }*/

        if (!is_null($this->limit)) {
            $query .= " LIMIT {$this->limit}";

            // Only add OFFSET if LIMIT is set
            if (!is_null($this->offset)) {
                $query .= " OFFSET {$this->offset}";
            }
        }

        return $query;
    }

    /**
     * Builds an INSERT query string.
     *
     * @return string The generated INSERT query.
     */
/*    protected function buildInsertQuery(): string
    {
        $query = $this->ignore ? 'INSERT IGNORE INTO' : 'INSERT INTO';
        $query .= " {$this->table} (";

        $columns = implode(', ', array_keys($this->insertData));
        $values = ':' . implode(', :', array_keys($this->insertData));

        $query .= "{$columns}) VALUES ({$values})";

        if (!empty($this->onDuplicateKeyUpdate)) {
            $updatePairs = [];
            foreach ($this->onDuplicateKeyUpdate as $column => $value) {
                $updatePairs[] = "{$column} = :duplicate_{$column}";
            }
            $query .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $updatePairs);
        }

        return $query;
    }*/

    protected function buildInsertQuery(): string
    {
        $query = $this->ignore ? 'INSERT IGNORE INTO' : 'INSERT INTO';
        $query .= " {$this->table} (";

        $columns = implode(', ', array_keys($this->insertData));
        $placeholders = ':' . implode(', :', array_keys($this->insertData));

        $query .= "{$columns}) VALUES ({$placeholders})";

        if (!empty($this->onDuplicateKeyUpdate)) {
            $updatePairs = [];
            foreach ($this->onDuplicateKeyUpdate as $column => $value) {
                $updatePairs[] = "{$column} = :duplicate_{$column}";
                $this->bindings[":duplicate_{$column}"] = $value;
            }
            $query .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $updatePairs);
        }

        // Bind values for insertData
        foreach ($this->insertData as $column => $value) {
            $this->bindings[":{$column}"] = $value;
        }

        return $query;
    }


    /**
     * Builds a batch INSERT query string.
     *
     * @return string The generated batch INSERT query.
     */
    /*protected function buildBatchInsertQuery(): string
    {
        $query = $this->ignore ? 'INSERT IGNORE INTO' : 'INSERT INTO';
        $query .= " {$this->table} (";

        $columns = array_keys(reset($this->batchInsertData));
        $query .= implode(', ', $columns) . ') VALUES ';

        $rows = [];
        foreach ($this->batchInsertData as $index => $row) {
            $placeholders = array_map(fn($col) => ":batch_{$index}_{$col}", array_keys($row));
            $rows[] = '(' . implode(', ', $placeholders) . ')';
        }

        $query .= implode(', ', $rows);

        return $query;
    }*/
    protected function buildBatchInsertQuery(): string
    {
        $query = $this->ignore ? 'INSERT IGNORE INTO' : 'INSERT INTO';
        $query .= " {$this->table} (";

        $columns = array_keys(reset($this->batchInsertData));
        $query .= implode(', ', $columns) . ') VALUES ';

        $rows = [];
        foreach ($this->batchInsertData as $index => $row) {
            $placeholders = array_map(fn($col) => ":batch_{$index}_{$col}", array_keys($row));
            $rows[] = '(' . implode(', ', $placeholders) . ')';
        }

        $query .= implode(', ', $rows);

        // Bind values for batchInsertData
        foreach ($this->batchInsertData as $index => $row) {
            foreach ($row as $column => $value) {
                $this->bindings[":batch_{$index}_{$column}"] = $value;
            }
        }

        return $query;
    }

    /**
     * Builds an UPDATE query string.
     *
     * @return string The generated UPDATE query.
     */
    /*protected function buildUpdateQuery(): string
    {
        $query = "UPDATE {$this->table} SET ";

        $updates = [];
        foreach ($this->updateData as $column => $value) {
            $updates[] = "{$column} = :update_{$column}";
        }

        $query .= implode(', ', $updates);
        $query .= $this->buildWhereClause();

        return $query;
    }*/
    protected function buildUpdateQuery(): string
    {
        $query = "UPDATE {$this->table} SET ";

        $updates = [];
        foreach ($this->updateData as $column => $value)
        {
            if($this->primaryKey != $column){
                $updates[] = "{$column} = :update_{$column}";
                $this->bindings[":update_{$column}"] = $value;
            }
            else{
                $this->where($column,'=',$value);
            }
        }

        $query .= implode(', ', $updates);

        $query .= $this->buildWhereClause();

        return $query;
    }

    /**
     * Builds a DELETE query string.
     *
     * @return string The generated DELETE query.
     */
    protected function buildDeleteQuery(): string
    {
        $query = "DELETE FROM {$this->table}";
        $query .= $this->buildWhereClause();
        return $query;
    }

    /**
     * Builds a soft delete query string.
     *
     * @return string The generated soft delete query.
     */
    protected function buildSoftDeleteQuery(): string
    {
        $query = "UPDATE {$this->table} SET {$this->softDeleteColumn} = 1";
        $query .= $this->buildWhereClause();
        return $query;
    }

    /**
     * Executes the built query and returns the statement.
     *
     * @return PDOStatement The prepared statement.
     * @throws Exception If the query execution fails.
     */
    public function executeQuery(): PDOStatement
    {
        $statement = $this->statement();
        if (!$statement->execute()) {
            throw new Exception('Query execution failed: ' . implode(', ', $statement->errorInfo()));
        }

        return $statement;
    }

    /**
     * @throws Exception
     */
    public function getResult($mode = PDO::FETCH_BOTH, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0): mixed
    {
        try {
            $statement = $this->executeQuery();
            return $statement->fetch($mode,$cursorOrientation,$cursorOffset);
        } catch (Exception $e) {
            throw new Exception("Database Exception: QueryBuilder->fetch \n".$e->getMessage() );
        }
    }

    /**
     * @throws Exception
     */
    public function getResults($mode = PDO::FETCH_ASSOC, ...$args): bool|array
    {
        try {
            $statement = $this->executeQuery();
            return $statement->fetchAll($mode,...$args);
        } catch (Exception $e) {
            throw new Exception("Database Exception: QueryBuilder->fetch \n".$e->getMessage(),$e->getCode());
        }
    }

    /**
     * @throws Exception
     */
    public function count():int{
        $statement = $this->executeQuery();
        return $statement->rowCount();
    }

    /**
     * @throws Exception
     */
    /*protected function statement():PDOStatement
    {
        $query = $this->build();
        $statement = $this->prepare($query);

        foreach ($this->where as $index => $condition) {
            if(isset($condition['value']) && isset($condition['operator']))
                $statement->bindValue(":where_{$index}", $condition['value']);
        }

        if ($this->queryType === 'insert') {
            foreach ($this->insertData as $column => $value) {
                $statement->bindValue(":{$column}", $value);
            }
        }

        if ($this->queryType === 'update') {
            foreach ($this->updateData as $column => $value) {
                $statement->bindValue(":update_{$column}", $value);
            }
        }

        if ($this->queryType === 'batchInsert') {
            foreach ($this->batchInsertData as $index => $row) {
                foreach ($row as $column => $value) {
                    $statement->bindValue(":batch_{$index}_{$column}", $value);
                }
            }
        }

        if (!empty($this->onDuplicateKeyUpdate)) {
            foreach ($this->onDuplicateKeyUpdate as $column => $value) {
                $statement->bindValue(":duplicate_{$column}", $value);
            }
        }

        return $statement;
    }*/

    protected function statement(): PDOStatement
    {
        $query = $this->build();
        $statement = $this->prepare($query);

        // Bind where conditions
        foreach ($this->bindings as $key => $value) {
            $statement->bindValue($key, $value);
        }

        return $statement;
    }

    abstract protected function prepare( $query, $options = []): PDOStatement|false;

    abstract public function lastInsertId(): int;

}
