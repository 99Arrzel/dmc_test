<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('users')->group(function () {
    Route::controller(App\Http\Controllers\DmcUsersController::class)->group(function () {
        Route::get('/list', 'ListUsers');
        Route::post('/create', 'CreateUser');
        Route::put('/update', 'UpdateUser');
        Route::delete('/delete', 'DeleteUser');
    });
});
