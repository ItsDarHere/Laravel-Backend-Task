<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\FeedBackController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("login", [ApiAuthController::class, 'index']);
Route::post("register", [ApiAuthController::class, 'CreateUser']);

Route::prefix("feedback")->group(function(){
    Route::post("/read",[FeedBackController::class, "read"]);
    Route::post("/store",[FeedBackController::class, "store"]);
    Route::post('/delete/{id}',[FeedBackController::class, "delete"]);
});

Route::prefix("comment")->group(function(){
    Route::post("/",[CommentController::class, "index"]);
    Route::post("/store",[CommentController::class, "store"]);
    Route::get("/readbyid/{id?}",[CommentController::class, "readById"]);
});

Route::prefix("user")->group(function(){
    Route::get("/read",[UserController::class, "getAll"]);
    Route::post('/delete/{id}', [UserController::class, 'delete']);
});