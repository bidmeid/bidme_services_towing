<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_type_kendaraan extends Model
{
    protected $table = 'tbl_type_kendaraan';
	
	protected $primaryKey = 'id';
	
	//protected $guarded = [];
	
	protected $fillable = ['id','typeKendaraan'];
}