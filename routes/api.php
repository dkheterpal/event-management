<?php

// use App\Http\Controllers\Api\EventController;
// use App\Http\Controllers\Api\AttendeeController;
// use App\Http\Controllers\Api\AuthController;



// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// //Adding authentication except

// // Public routes
// Route::apiResource('events', EventController::class)
//     ->only(['index', 'show']);

//     // Protected routes
// Route::apiResource('events', EventController::class)
// ->only(['store', 'update', 'destroy'])
// ->middleware(['auth:sanctum', 'throttle:api']);

// // Protected routes
// Route::apiResource('events.attendees', AttendeeController::class)
// ->scoped()
// ->only(['store', 'destroy'])
// ->middleware(['auth:sanctum', 'throttle:api']);


// // Public routes
// Route::apiResource('events.attendees', AttendeeController::class)
// ->scoped()
// ->only(['index', 'show']);


// Route::post('/login', [AuthController::class,'login']);
// Route::apiResource('events', EventController::class);
// Route::apiResource('events.attendees', AttendeeController::class)
//     // ->scoped(['attendee' => 'event']);
//     ->scoped()->except('update'); //Except update, we don't need it.
//     //Scoped means these attendee resources are part of an event. and if we will use route binding and call the attendee using /events/attendee, laravel will automatically call the attendee of the given event.
//     //Only attendees associated with a particular event can be fetched, we can't pull attendees without that given event

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 🔹 Public routes (no authentication required)
Route::apiResource('events', EventController::class)->only(['index', 'show']);
Route::apiResource('events.attendees', AttendeeController::class)->only(['index', 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class,'logout'])
    ->middleware('auth:sanctum');
// 🔹 Protected routes (require authentication)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('events', EventController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('events.attendees', AttendeeController::class)->scoped()->only(['store', 'destroy']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});
