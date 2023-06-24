<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpensesController;

Route::resource('income', IncomeController::class);

Route::resource('expenses', ExpensesController::class);




