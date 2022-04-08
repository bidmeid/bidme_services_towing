<?php

 use App\Http\Controllers\API ;
	
	 
		 Route::prefix('/api/app')->group(function () {
            Route::get('/merekKendaraan', [Api\MerekKendaraan::class, 'index']);
            Route::get('/typeKendaraan', [Api\TypeKendaraan::class, 'index']);
        });
	 
	 
	