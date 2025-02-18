<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('events', EventController::class);
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped(['attendee' => 'event']);
    //Scoped means these attendee resources are part of an event. and if we will use route binding and call the attendee using /events/attendee, laravel will automatically call the attendee of the given event.


