<?php

namespace App\Core;

use Exception;
use PDO;
use PDOStatement;

class Database
{
    /**
     * Database Connection
     *
     * @var PDO|null
     */
    protected ?PDO $connection = null;

    /**
     * Database Query Statement
     *
     * @var PDOStatement|null
     */
    protected ?PDOStatement $statement = null;

    /**
     * Default Database Options
     */
    protected const DEFAULT_OPTIONS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * Database Constructor.
     *
     * @param array $config
     * @param PDO|null $pdo Optional existing PDO connection.
     */
    public function __construct(array $config, ?PDO $pdo = null)
    {
        if ($pdo !== null) {
            $this->connection = $pdo;

            return;
        }

        $this->checkConfig($config);

        $this->connection = new PDO(
            $this->buildDSN($config),
            $config['user'] ?? null,
            $config['pass'] ?? null,
            self::DEFAULT_OPTIONS
        );
    }

    /**
     * Execute a database query.
     *
     * @param string $query
     * @param array $params
     * @return Database
     *
     * @throws Exception If no database connection.
     */
    public function query(string $query, array $params = []): Database
    {
        if ($this->connection === null) {
            throw new Exception('No database connection.');
        }

        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);

        return $this;
    }

    /**
     * Fetch all rows.
     *
     * @param array $options
     * @return array|bool
     */
    public function get(array $options = []): array|bool
    {
        return $this->statement?->fetchAll(...$options);
    }

    /**
     * Fetch the first row.
     *
     * @param array $options
     * @return array|bool
     */
    public function first(array $options = []): array|bool
    {
        return $this->statement?->fetch(...$options);
    }

    /**
     * Validate database configuration.
     *
     * @param array $config
     *
     * @throws Exception If configuration is invalid.
     */
    protected function checkConfig(array $config): void
    {
        $requiredKeys = [
            'host',
            'name',
            'user',
            'pass',
        ];

        foreach ($requiredKeys as $key) {
            if (empty($config[$key])) {
                throw new Exception("Missing or null '$key' in database configuration.");
            }
        }
    }

    /**
     * Build the Data Source Name string.
     *
     * @param array $config
     * @return string
     */
    protected function buildDSN(array $config): string
    {
        return sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['name']);
    }
}
