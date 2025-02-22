<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //role admin and registrador
        $role = [
            ['name' => 'admin'],
            ['name' => 'entrada'],
            ['name' => 'salida'],
        ];

        DB::table('roles')->insert($role);
    }
}
