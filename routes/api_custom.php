<?php

use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\UserSubscriptionController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('subscription',SubscriptionController::class);
    Route::resource('user-subscription',UserSubscriptionController::class);
    Route::post('user-subscription/subscribe/{subscription}',[UserSubscriptionController::class,'subscribe']);
    Route::delete('user-subscription/subscribe/{userSubscription}',[UserSubscriptionController::class,'cancel']);
    Route::post('user-subscription/subscribe/{userSubscription}/pay',[UserSubscriptionController::class,'pay']);
    Route::get('users/subscriptions-and-transactions',[\App\Http\Controllers\API\UserController::class,'indexAnd']);
    Route::get('users/subscriptions-with-transactions',[\App\Http\Controllers\API\UserController::class,'indexWith']);
});

