<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;

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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/category/{category}/tasks', [CategoryController::class, 'tasks']);
    Route::patch('/task', [TaskController::class, 'sort']);
    Route::apiResources([
        '/category' => CategoryController::class,
        '/task' => TaskController::class
    ]);
});
