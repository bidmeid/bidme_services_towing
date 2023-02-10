<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SosialAccountMitra extends Model
{
    use HasFactory;
	protected $table = 'sosial_accounts_mitra';
    protected $fillable = ['provider_id', 'provider_name'];

    public function user()
    {
        return $this->belongsTo(Tbl_user_mitra::class, 'tbl_user_mitra_id');
    }
}
