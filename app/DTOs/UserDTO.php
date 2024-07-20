<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) for User entities.
 *
 * This class provides a structured and immutable representation of a user,
 * ensuring data integrity and facilitating data transfer operations within the application.
 */
readonly class UserDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {
        // ...
    }
}
