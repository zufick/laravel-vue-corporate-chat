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
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
            ],
            'text' =>  $this->text,
            'created_at' => $this->created_at
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
