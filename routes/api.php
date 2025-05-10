<?php

use App\Http\Controllers\API\AttendeeController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('events')->group(
    function(){
        Route::get('lists', [EventController::class, 'getList']);
        Route::post('create', [EventController::class, 'create']);
        Route::post('{event}/update', [EventController::class, 'update']);
        Route::delete('{event}/delete', [EventController::class, 'delete']);
    }
);

Route::prefix('attendee')->group(
    function(){
        Route::post('create', [AttendeeController::class, 'create']);
        Route::post('{attendee}/update', [AttendeeController::class, 'update']);
        Route::get('{attendee}/show', [AttendeeController::class, 'show']);
    }
);

Route::prefix('booking')->group(
    function(){
        Route::post('create', [BookingController::class, 'create']);
    }
);
