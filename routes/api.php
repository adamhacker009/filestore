<?php

use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/authorization', [UserController::class, 'authorization']);
Route::post('/registration', [UserController::class, 'registration']);
Route::post('/login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/logout', [UserController::class, 'logout']);
});
route::prefix("files")->group(function(){
    route::get('/{id}', [FileController::class, 'downloadFile'])->middleware('auth:sanctum');
    route::get('/get', [FileController::class, 'getAllFiles'])->middleware('auth:sanctum');
    route::post('/add', [FileController::class, 'addFile'])->middleware('auth:sanctum');
    route::patch('/{id}', [FileController::class, 'renameFile'])->middleware('auth:sanctum');
    route::post('/{id}/accesses', [FileController::class, 'accesses'])->middleware('auth:sanctum');
});
