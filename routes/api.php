<?php

use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;

Route::post('/materiales', [MaterialController::class, 'store']);