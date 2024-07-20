<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $usersRepo;

    public function __construct(UserRepository $usersRepo)
    {
        $this->usersRepo = $usersRepo;
    }

    /**
     * Get users with optional limit and offset.
     *
     * @param int $limit Number of users to retrieve.
     * @param int $offset Offset for the query.
     * @return array<UserDTO> Collection of UserDTO objects.
     */
    public function get(int $limit = 4, int $offset = 0): array
    {
        $rows = $this->usersRepo->get($limit, $offset);
        $results = [];

        if (!$rows) {
            return [];
        }

        foreach ($rows as $row) {
            $results[] = new UserDTO(...$row);
        }

        return $results;
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The user's ID.
     * @return UserDTO|null UserDTO if found, otherwise null.
     */
    public function getByID(int $id): ?UserDTO
    {
        $row = $this->usersRepo->getByID($id);

        if (!$row) {
            return null;
        }

        return new UserDTO(...$row);
    }
}
