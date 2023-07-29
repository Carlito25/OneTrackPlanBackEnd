<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Http\Requests\IncomeRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IncomeController extends Controller
{
    // public function index(Request $request)
    // {
    //     $income = Income::get();

    //     return response()->json($income);

    // }

    public function getUserIncomes($userId)
    {
        $incomes = Income::where('user_id', $userId)->get();

        return response()->json($incomes);
    }

    public function getUserIncomesWeekly($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(7);
        $endDate = Carbon::now($timezone);

        $incomes = Income::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return response()->json($incomes);
    }

    public function getUserIncomesMonthly($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(30);
        $endDate = Carbon::now($timezone);

        $incomes = Income::where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return response()->json($incomes);
    }
    public function store(IncomeRequest $request)
    {
        try {
            $inc = Income::updateOrCreate(
                ['id' => $request['income_id']],
                [
                    'user_id' => $request['user_id'],
                    'date' => $request['date'],
                    'income' => $request['income'],
                    'amount' => $request['amount'],
                ]
            );
            if ($inc->wasRecentlyCreated) {
                return response()->json([
                    'status' => "Created",
                    'message' => "Income Successfully Created"
                ]);
            } else {
                return response()->json([
                    'status' => "Updated",
                    'message' => "Income Successfully Updated"
                ]);
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function show(Income $income)
    {
        $income = Income::where('id', $income->id)
            ->first();

        return response()->json($income);
    }

    public function destroy(Income $income)
    {
        try {
            $income->delete();
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function getMonthlyTotal($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(30);
        $endDate = Carbon::now($timezone);

        $incomeTotal = DB::table('incomes')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json(['incomeTotal' => $incomeTotal]);
    }



    public function getWeeklyTotal($userId)
    {
        $timezone = 'Asia/Manila';
        $startDate = Carbon::now($timezone)->subDays(7);
        $endDate = Carbon::now($timezone);

        $incomeWeeklyTotal = DB::table('incomes')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json(['incomeWeeklyTotal' => $incomeWeeklyTotal]);
    }

    public function getDailyTotal($userId)
    {
        $timezone = 'Asia/Manila';
        $dateToday = Carbon::now($timezone);

        $incomeDailyTotal = DB::table('incomes')
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->where('date', $dateToday->toDateString())
            ->sum('amount');

        return response()->json(['incomeDailyTotal' => $incomeDailyTotal]);
    }
}
