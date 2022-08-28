<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StorageController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'storage'], function () {
    Route::get('/ls', [StorageController::class, 'index']);
    Route::get('/download-file', [StorageController::class, 'downloadFile']);
    Route::post('/make-directory', [StorageController::class, 'makeDirectory']);
    Route::post('/upload-file', [StorageController::class, 'uploadFile']);
    Route::put('/rename-file', [StorageController::class, 'renameFile']);
    Route::delete('/delete-file', [StorageController::class, 'deleteFile']);
});
