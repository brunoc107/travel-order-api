<?php

namespace Domain\User\ValueObjects;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
