<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TeamController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    // Fetch logged-in employee's profile
    Route::get('/profile', [EmployeeController::class, 'getProfile']);

    // Update employee's profile
    Route::put('/profile', [EmployeeController::class, 'updateProfile']);
    Route::put('/employees/{id}', [EmployeeController::class, 'updateEmployee']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'deleteEmployee']);
    Route::get('/dashboard-stats', [EmployeeController::class, 'dashboardStats']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    // Route::get('/projects', [ProjectController::class, 'getProjects']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'createTeam']);
    Route::delete('/teams/{team}', [TeamController::class, 'destroy']);
    Route::post('/teams/assign-employee', [TeamController::class, 'assignEmployeeToTeam']);
    Route::post('/teams/assign-project', [TeamController::class, 'assignProjectToTeam']);
    Route::post('/teams/assign-team-to-project', [TeamController::class, 'assignTeamToProject']);
});
