<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpensesController;

Route::resource('income', IncomeController::class);
Route::get('/incomeMonthlyTotal', 'App\Http\Controllers\IncomeController@getMonthlyTotal');
Route::get('/incomeWeeklyTotal', 'App\Http\Controllers\IncomeController@getWeeklyTotal');
Route::get('/incomeDailyTotal', 'App\Http\Controllers\IncomeController@getDailyTotal');

Route::resource('expenses', ExpensesController::class);
Route::get('/expensesMonthlyTotal', 'App\Http\Controllers\ExpensesController@getMonthlyTotal');
Route::get('/expensesWeeklyTotal', 'App\Http\Controllers\ExpensesController@getWeeklyTotal');
Route::get('/expensesDailyTotal', 'App\Http\Controllers\ExpensesController@getDailyTotal');






