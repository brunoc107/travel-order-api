<?php

namespace Domain\User\Entities;

use Domain\User\ValueObjects\UserRole;

class User
{
    private string $id {
        get {
            return $this->id;
        }
    }

    private string $name {
        get {
            return $this->name;
        }
    }

    private string $email {
        get {
            return $this->email;
        }
    }

    private string $password {
        get {
            return $this->password;
        }
    }

    private UserRole $role {
        get {
            return $this->role;
        }
    }

    public function __construct(string $id, string $name, string $email, string $password, UserRole $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }
}
