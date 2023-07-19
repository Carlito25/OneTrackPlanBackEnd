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
Route::get('/incomeMonthlyTotal/user_id/{userId}', 'App\Http\Controllers\IncomeController@getMonthlyTotal');
Route::get('/incomeWeeklyTotal/user_id/{userId}', 'App\Http\Controllers\IncomeController@getWeeklyTotal');
Route::get('/incomeDailyTotal/user_id/{userId}', 'App\Http\Controllers\IncomeController@getDailyTotal');
Route::get('incomes/user_id/{userId}', 'App\Http\Controllers\IncomeController@getUserIncomes');


Route::resource('expenses', ExpensesController::class);
Route::get('/expensesMonthlyTotal/user_id/{userId}', 'App\Http\Controllers\ExpensesController@getMonthlyTotal');
Route::get('/expensesWeeklyTotal/user_id/{userId}', 'App\Http\Controllers\ExpensesController@getWeeklyTotal');
Route::get('/expensesDailyTotal/user_id/{userId}', 'App\Http\Controllers\ExpensesController@getDailyTotal');
Route::get('expenses/user_id/{userId}', 'App\Http\Controllers\ExpensesController@getUserExpenses');

Route::middleware('throttle:60,1')->group(function () {
    Route::resource('task', TaskController::class);
    Route::put('task/{id}', [TaskController::class, 'updateTaskStatus']);
    Route::get('task/user_id/{userId}', 'App\Http\Controllers\TaskController@getUserTask');
});

Route::get('/taskCompleted/user_id/{userId}', [TaskController::class, 'getCompletedTask']);

Route::resource('contentplanner', ContentPlannerController::class);
Route::get('/contentDraft/user_id/{userId}', 'App\Http\Controllers\ContentPlannerController@getDraftContent');
Route::get('/contentScheduled/user_id/{userId}', 'App\Http\Controllers\ContentPlannerController@getScheduledContent');
Route::get('/contentPublished/user_id/{userId}', 'App\Http\Controllers\ContentPlannerController@getPublishedContent');
Route::get('contentplanner/user_id/{userId}', 'App\Http\Controllers\ContentPlannerController@getUserContent');


Route::resource('user', UserController::class);

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);







