<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_jenis_kendaraan extends Model
{
    protected $table = 'tbl_jenis_kendaraan';
	
	protected $primaryKey = 'id';
	
	//protected $guarded = [];
	
	protected $fillable = ['id','jenisKendaraan'];
}