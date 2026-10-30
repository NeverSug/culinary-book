<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Анна', 'email' => 'anna@example.com'],
            ['name' => 'Борис', 'email' => 'boris@example.com'],
            ['name' => 'Вера', 'email' => 'vera@example.com'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        $this->command->info('Засеяно ' . count($users) . ' пользователей (пароль: password).');
    }
}
