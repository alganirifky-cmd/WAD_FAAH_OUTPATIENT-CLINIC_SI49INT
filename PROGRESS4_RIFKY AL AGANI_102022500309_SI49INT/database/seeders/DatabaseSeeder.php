<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /** Password kedua akun: "password" */
    public function run(): void
    {
        foreach ([
            ['Admin Klinik', 'admin@clinic.test', 'admin'],
            ['Staff Klinik', 'staff@clinic.test', 'staff'],
        ] as [$name, $email, $role]) {
            // forceCreate: 'role' sengaja tidak masuk $fillable (aman dari mass assignment)
            User::query()->where('email', $email)->exists()
                || User::forceCreate([
                    'name' => $name, 'email' => $email, 'role' => $role,
                    'password' => Hash::make('password'),
                ]);
        }
    }
}
