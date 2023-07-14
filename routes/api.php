<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ContentPlannerController;
use App\Http\Controllers\UserController;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::resource('income', IncomeController::class);
Route::get('/incomeMonthlyTotal', 'App\Http\Controllers\IncomeController@getMonthlyTotal');
Route::get('/incomeWeeklyTotal', 'App\Http\Controllers\IncomeController@getWeeklyTotal');
Route::get('/incomeDailyTotal', 'App\Http\Controllers\IncomeController@getDailyTotal');

Route::resource('expenses', ExpensesController::class);
Route::get('/expensesMonthlyTotal', 'App\Http\Controllers\ExpensesController@getMonthlyTotal');
Route::get('/expensesWeeklyTotal', 'App\Http\Controllers\ExpensesController@getWeeklyTotal');
Route::get('/expensesDailyTotal', 'App\Http\Controllers\ExpensesController@getDailyTotal');

Route::middleware('throttle:60,1')->group(function () {
    Route::resource('task', TaskController::class);
    Route::put('task/{id}', [TaskController::class, 'updateTaskStatus']);
});

Route::get('/taskCompleted', [TaskController::class, 'getCompletedTask']);

Route::resource('contentplanner', ContentPlannerController::class);
Route::get('/contentDraft', 'App\Http\Controllers\ContentPlannerController@getDraftContent');
Route::get('/contentScheduled', 'App\Http\Controllers\ContentPlannerController@getScheduledContent');
Route::get('/contentPublished', 'App\Http\Controllers\ContentPlannerController@getPublishedContent');

Route::resource('user', UserController::class);

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

// Route::middleware('auth:api')->group(function () {
//     Route::get('/is-logged-in', function () {
//         return response()->json(['isLoggedIn' => true]);
//     });
// });

// Route::fallback(function () {
//     return response()->json(['isLoggedIn' => false]);
// });






