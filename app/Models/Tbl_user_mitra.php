<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Tbl_user_mitra extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	
	protected $table = 'tbl_user_mitra';
	
	protected $primaryKey = 'id';
	protected $fillable = [
        'name',
        'no_telp',
        'email',
        'password',
    ];
	protected $hidden = [
        'password',
        'remember_token',
    ];
	
	protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	public function SosialAccountMitra()
    {
        return $this->hasMany(SosialAccountMitra::class);
    }
	
}