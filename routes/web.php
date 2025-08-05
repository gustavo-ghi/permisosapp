<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AppClientController;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('home');
});


Route::middleware('auth')->group(function () {
    
    Route::get('/home', function() {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('apps.index');
        }
        return view('home'); 
    })->name('home');

    Route::middleware('admin')->group(function () {
        Route::resource('apps', AppClientController::class)->except(['show']);
    });

  Route::post('apps/{app}/regenerate-token', [AppClientController::class, 'regenerateToken'])->name('apps.regenerateToken');
});

