<?php


use App\Http\Controllers\API2\SubscriptionController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('user/{user}/subscription',[\App\Http\Controllers\API2\SubscriptionController::class,'subscribe']);
    Route::put('user/{user}/subscription/{subscription}',[\App\Http\Controllers\API2\SubscriptionController::class,'update']);
    Route::delete('user/{user}/subscription/{subscription}',[\App\Http\Controllers\API2\SubscriptionController::class,'delete']);
    Route::post('user/{user}/transaction',[\App\Http\Controllers\API2\SubscriptionController::class,'transaction']);
    Route::get('user/{user}',[\App\Http\Controllers\API2\SubscriptionController::class,'index']);
});

