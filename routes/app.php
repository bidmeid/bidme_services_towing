<?php

use App\Http\Controllers\API;
use Illuminate\Support\Facades\Route;


Route::prefix('/api/app')->group(function () {
    Route::get('/jenisKendaraan', [Api\JenisKendaraan::class, 'index']);
    Route::get('/merekKendaraan', [Api\MerekKendaraan::class, 'index']);
    Route::get('/typeKendaraan', [Api\TypeKendaraan::class, 'index']);
    Route::get('/kondisiKendaraan', [Api\KondisiKendaraan::class, 'index']);
    Route::get('/bankList', [Api\MetaData::class, 'bank']);
});
