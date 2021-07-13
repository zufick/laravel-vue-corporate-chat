<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert([
            'name' => 'Основной',
            'owner_id' => '1'
        ]);
        DB::table('rooms')->insert([
            'name' => 'Объявления',
            'owner_id' => '1'
        ]);
        foreach (User::all() as $user){
            $user->joinGeneralRooms();
        }
    }
}
