<?php

 use App\Http\Controllers\API ;
	
	 
		 Route::prefix('/api/app')->group(function () {
            Route::get('/jenisKendaraan', [Api\JenisKendaraan::class, 'index']);
            Route::get('/merekKendaraan', [Api\MerekKendaraan::class, 'index']);
            Route::get('/typeKendaraan', [Api\TypeKendaraan::class, 'index']);
            Route::get('/kondisiKendaraan', [Api\KondisiKendaraan::class, 'index']);
        });
	 
	 
	