<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();



        $this->call([
            EmployesSeeder::class,
            CategorySeeder::class,
            RoleSeeder::class,
            ItemSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'admin User',
            'email' => 'admin@gmail.com',
            'role_id' => 1,
            'password' => Hash::make('admin21'),
        ]);

        User::factory()->create([
            'name' => 'registrador User',
            'email' => 'registrador@gmail.com',
            'role_id' => 2,
            'password' => Hash::make('registrador12'),
        ]);

        User::factory()->create([
            'name' => 'salida User',
            'email' => 'salida@gmail.com',
            'role_id' => 3,
            'password' => Hash::make('salida12'),
        ]);

        // $this->call(EmployesSeeder::class);
    }
}
