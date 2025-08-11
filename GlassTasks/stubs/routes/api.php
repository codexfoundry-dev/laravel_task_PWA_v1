<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProjectsController;
use App\Http\Controllers\Api\V1\TasksController;
use App\Http\Controllers\Api\V1\CommentsController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\CalendarController;
use App\Http\Controllers\Api\V1\InvitationsController;
use App\Http\Controllers\Api\V1\ImportController;

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', fn () => request()->user());

    Route::apiResource('projects', ProjectsController::class);
    Route::post('projects/{project}/invite', [ProjectsController::class, 'invite']);
    Route::post('projects/{project}/join', [ProjectsController::class, 'join']);

    Route::apiResource('tasks', TasksController::class);
    Route::post('tasks/{task}/move', [TasksController::class, 'move']);
    Route::post('tasks/{task}/complete', [TasksController::class, 'complete']);

    Route::get('tasks/{task}/comments', [CommentsController::class, 'index']);
    Route::post('tasks/{task}/comments', [CommentsController::class, 'store']);

    Route::get('search', [SearchController::class, 'index']);

    Route::get('calendar', [CalendarController::class, 'feed']);
    Route::get('calendar.ics', [CalendarController::class, 'ics']);

    Route::post('import/tasks', [ImportController::class, 'tasks']);
});