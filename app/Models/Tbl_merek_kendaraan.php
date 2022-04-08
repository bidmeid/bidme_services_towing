<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_merek_kendaraan extends Model
{
    protected $table = 'tbl_merek_kendaraan';
	
	protected $primaryKey = 'id';
	
	//protected $guarded = [];
	
	protected $fillable = ['id','merekKendaraan'];
}