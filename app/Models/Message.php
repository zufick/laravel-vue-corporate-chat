<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'room_id', 'text'];

    public function toArray()
    {
        return [
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
            ],
            'text' =>  $this->text,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
