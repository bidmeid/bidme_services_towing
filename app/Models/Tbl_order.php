<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_order extends Model
{
    protected $table = 'tbl_order';
	
	protected $primaryKey = 'id';
	
	protected $guarded = [];
	
	public function Tbl_customer(){
		return $this->belongsTo(Tbl_customer::class, 'id');
	}
}