<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SosialAccountCustomer extends Model
{
    use HasFactory;
	protected $table = 'sosial_accounts_customer';
    protected $fillable = ['provider_id', 'provider_name'];

    public function user()
    {
        return $this->belongsTo(Tbl_customer::class, 'tbl_customer_id');
    }
}
