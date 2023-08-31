<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::group(['middleware'=>'IsUser'],function(){
    Route::get('/dashboard',[UserController::class, 'index'])->name('dashboard');
});