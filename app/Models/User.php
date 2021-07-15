<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use phpDocumentor\Reflection\Types\This;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin',
        'approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Одобренные пользователи
     * @return mixed
     */
    public static function approved(){
        return self::where('approved', 1);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_users')
            ->withTimestamps();
    }

    public function canJoinRoom($roomId)
    {
        /**
         * Пользователь всегда может зайти в основной канал
         */
        if($roomId == 1)
            return true;

        $room = $this->rooms->where('id', $roomId)->first();
        return $room ? true : false;
    }

    public function canModerateRoom($roomId)
    {
        $room = Room::find($roomId);
        if(!$room
        || !($room->owner_id == $this->id || $this->admin || $room->moderators->where('id', $this->id)->first())
        )
            return false;

        return true;
    }

    public function joinGeneralRooms()
    {
        RoomUser::create(['room_id' => 1, 'user_id' => $this->id]);
        RoomUser::create(['room_id' => 2, 'user_id' => $this->id]);
    }
}
