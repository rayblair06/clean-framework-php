<?php

namespace App\Repositories;

use RuntimeException;

class UserRepository extends Repository
{
    /**
     * Table name
     *
     * @return string
     */
    protected function getTable(): string
    {
        return 'users';
    }

    /**
     * Get all users.
     *
     * @return array An array of all user records.
     */
    public function get(): array
    {
        try {
            return $this->all();
        } catch (RuntimeException $e) {
            throw new RuntimeException('Failed to get users.', 0, $e);
        }
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The user's ID.
     * @return array|false The user's data as an associative array, or false if not found.
     */
    public function getByID(int $id)
    {
        try {
            $user = $this->find($id);

            if (!$user) {
                return false;
            }

            return $user;
        } catch (RuntimeException $e) {
            throw new RuntimeException("Failed to get user with ID {$id}.", 0, $e);
        }
    }
}
