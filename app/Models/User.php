<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
    ];

    protected $hidden = [
        'password'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }  
}
