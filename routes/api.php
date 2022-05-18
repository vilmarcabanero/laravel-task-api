<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
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

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('tasks', [TaskController::class, 'getTasks']);
    Route::post('tasks', [TaskController::class, 'createTask']);
    Route::get('tasks/active', [TaskController::class, 'getActiveTasks']);
    Route::get('tasks/complete', [TaskController::class, 'getCompleteTasks']);
    Route::patch('tasks/archive', [TaskController::class, 'archive']);
    Route::patch('tasks/complete/{id}', [TaskController::class, 'makeComplete']);
    Route::patch('tasks/incomplete/{id}', [TaskController::class, 'makeIncomplete']);
    Route::patch('tasks/{id}', [TaskController::class, 'updateTask']);
    Route::get('tasks/{id}', [TaskController::class, 'getTask']);
    Route::delete('tasks/{id}', [TaskController::class, 'deleteTask']);
});
