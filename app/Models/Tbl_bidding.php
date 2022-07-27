<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_bidding extends Model
{
    protected $table = 'tbl_bidding';
	
	protected $primaryKey = 'id';
	
	protected $guarded = [];
	
	public function mitra()
    {
        return $this->belongsTo(Tbl_user_mitra::class);
		 
    }
	
}