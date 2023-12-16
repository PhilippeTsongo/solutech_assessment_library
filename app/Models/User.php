<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Post;
use App\Models\Role;
use App\Models\Comment;
use App\Models\UserToken;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'email',
        'name',
        'profile',
        'password',
        'role_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function tokens(){
    //     return $this->hasMany(UserToken::class);
    // }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    
}
