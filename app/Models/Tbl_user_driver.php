<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Tbl_user_driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	
	protected $guard = 'driver';
	
	protected $table = 'tbl_user_driver';
	
	protected $primaryKey = 'id';
	
	protected $fillable = [
        'name',
        'noTlpDriver',
        'email',
        'avatar',
        'alamatDriver',
    ];
	
	protected $hidden = [
        'password',
        'remember_token',
    ];
	
	protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	
}