<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppStatusController; 

Route::post('/check-status', [AppStatusController::class, 'checkStatus']);