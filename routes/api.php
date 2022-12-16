<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\PostsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// auth
Route::post('register', [AuthController::class ,'register']);
Route::post('login', [AuthController::class , 'login']);
Route::post('logout', [AuthController::class ,'logout'])->middleware('auth:sanctum');
Route::get('list-users', [AuthController::class ,'list'])->middleware('auth:sanctum');
Route::post('update-user/{userId}', [AuthController::class ,'update'])->middleware('auth:sanctum');
Route::post('create-user', [AuthController::class ,'create'])->middleware('auth:sanctum');
Route::get('get-user/{userId}', [AuthController::class ,'show'])->middleware('auth:sanctum');


Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::get('reset-password', [NewPasswordController::class, 'resetPassword']);

// posts
Route::get('posts', [PostsController::class, 'index'])->middleware('auth:sanctum');
Route::post('posts/create', [PostsController::class, 'create'])->middleware('auth:sanctum');
Route::get('posts/{post}', [PostsController::class, 'show'])->middleware('auth:sanctum');
Route::post('posts/{id}', [PostsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('posts/{id}', [PostsController::class, 'delete'])->middleware('auth:sanctum');



