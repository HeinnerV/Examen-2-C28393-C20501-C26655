<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;

Route::post('/materiales', [MaterialController::class, 'store']);

Route::put('/materiales/{codigo}', [MaterialController::class, 'update']);

Route::get('/materiales', [MaterialController::class, 'index']);