<?php

namespace App\Repositories;

use App\Core\Database;
use PDOException;

/**
 * Base repository class that provides common functionality for all repositories.
 */
abstract class Repository
{
    /**
     * The database connection instance.
     *
     * @var Database
     */
    protected Database $database;

    /**
     * Initialize the repository with a database connection.
     *
     * @param Database $database The database connection instance.
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get the table associated with the repository.
     *
     * This method must be implemented by child classes to specify the database table.
     *
     * @return string The database table name.
     */
    abstract protected function getTable(): string;

    /**
     * Find an entity by its ID.
     *
     * @param int $id The ID of the entity to find.
     * @return mixed The found entity or null if not found.
     */
    public function find(int $id)
    {
        try {
            return $this
                ->database
                ->query("SELECT * FROM {$this->getTable()} WHERE id = :id", ['id' => $id])
                ->first();
        } catch (PDOException $e) {
            throw new \RuntimeException('Failed to find entity', 0, $e);
        }
    }

    /**
     * Retrieve all entities.
     *
     * @return array An array of entities.
     */
    public function all(): array
    {
        try {
            return $this
                ->database
                ->query("SELECT * FROM {$this->getTable()}")
                ->get();
        } catch (PDOException $e) {
            throw new \RuntimeException('Failed to retrieve entities', 0, $e);
        }
    }
}
