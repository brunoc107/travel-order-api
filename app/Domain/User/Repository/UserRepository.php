<?php

namespace App\Domain\User\Repository;

use Domain\User\Entities\User;

interface UserRepository
{
    public function insert(User $user): void;

    public function update(User $user): void;

    public function findByEmail(int $id): ?User;

    public function findById(int $id): ?User;
}
