<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Администратор',
            'email' => 'admin@localhost',
            'password' => Hash::make('password'),
            'admin' => true,
            'approved' => true,
        ]);
        User::factory()->create(['approved' => false]);
        User::factory()->count(3)->create();
    }
}
