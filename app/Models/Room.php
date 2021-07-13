<?php

namespace App\Models;

use App\Events\UserRoomsUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'owner_id'];

    public function moderators()
    {
        return $this->belongsToMany(User::class, 'room_moderators', 'room_id', 'user_id');
    }

    public function addAdmins()
    {
        $admins = User::where('admin', true)->get();
        foreach ($admins as $admin){
            $this->addUser($admin);
        }
    }

    public function addUser(User $user)
    {
        try {
            RoomUser::create(['room_id' => $this->id, 'user_id' => $user->id]);
        }catch (\Exception $exception){
            Log::info("Room user already exists");
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'room_users', 'room_id', 'user_id');
    }

    public function dispatchRoomUpdateToUsers()
    {
        foreach($this->users as $roomUser){
            UserRoomsUpdated::dispatch($roomUser);
        }
    }
}
