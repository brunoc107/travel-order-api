<?php

namespace Database\Seeders;

use App\Infra\Database\Eloquent\OrderModel;
use App\Infra\Database\Eloquent\User;
use Domain\User\ValueObjects\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'John Doe',
                'password' => 'password',
                'role' => UserRole::USER,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Joe',
                'password' => 'password',
                'role' => UserRole::ADMIN,
            ]
        );
    }
}
