<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Tbl_customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'tbl_customer';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
	protected $guard = 'customer';
	
    protected $fillable = [
        'name',
        'no_telp',
        'email',
        'avatar',
        'password',
        'region',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function SosialAccountCustomer()
    {
        return $this->hasMany(SosialAccountCustomer::class);
    }
	
	public function Tbl_order()
    {
        return $this->hasMany(Tbl_order::class);
    }
}
