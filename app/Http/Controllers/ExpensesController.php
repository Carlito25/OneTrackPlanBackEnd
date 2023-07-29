<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenses;
use App\Http\Requests\ExpensesRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpensesController extends Controller
{
    // public function index()
    // {
    //     $expenses = Expenses::get();

    //     return response()->json($expenses);
    // }

    public function getUserExpenses($userId)
    {
        $expenses = Expenses::where('user_id', $userId)->get();

        return response()->json($expenses);
    }
    public function getUserExpensesWeekly($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(7);
        $endDate = Carbon::now($timezone);

        $expenses = Expenses::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return response()->json($expenses);
    }

    public function getUserExpensesMonthly($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(30);
        $endDate = Carbon::now($timezone);

        $expenses = Expenses::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return response()->json($expenses);
    }


    public function store(ExpensesRequest $request)
    {
        try {
            $exp = Expenses::updateOrCreate(
                ['id' => $request['expenses_id']],
                [
                    'user_id' => $request['user_id'],
                    'date' => $request['date'],
                    'expenses' => $request['expenses'],
                    'amount' => $request['amount'],
                ]
            );
            if ($exp->wasRecentlyCreated) {
                return response()->json([
                    'status' => "Created",
                    'message' => "Expenses Successfully Created"
                ]);
            } else {
                return response()->json([
                    'status' => "Updated",
                    'message' => "Expenses Successfully Updated"
                ]);
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }
    public function show(Expenses $expense)
    {
        $expense = Expenses::where('id', $expense->id)
            ->first();

        return response()->json($expense);
    }


    public function destroy(Expenses $expense)
    {
        try {
            $expense->delete();
            return response()->json('Expense deleted successfully');
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json('Error deleting expense', 500);
        }
    }

    public function getMonthlyTotal($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(30);
        $endDate = Carbon::now($timezone);

        $expensesMonthlyTotal = DB::table('expensestable')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json(['expensesMonthlyTotal' => $expensesMonthlyTotal]);
    }
    public function getWeeklyTotal($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(7);
        $endDate = Carbon::now($timezone);

        $expensesWeeklyTotal = DB::table('expensestable')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json(['expensesWeeklyTotal' => $expensesWeeklyTotal]);
    }

    public function getDailyTotal($userId)
    {
        $timezone = 'Asia/Manila';
        $dateToday = Carbon::now($timezone);

        $expensesDailyTotal = DB::table('expensestable')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->where('date', $dateToday->toDateString())
            ->sum('amount');

        return response()->json(['expensesDailyTotal' => $expensesDailyTotal]);
    }
}
