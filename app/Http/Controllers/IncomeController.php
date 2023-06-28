<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Http\Requests\IncomeRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function index()
    {
        $income = Income::get();

        return response()->json($income);
    }

    public function store(IncomeRequest $request)
    {
        try {
            $inc = Income::updateOrCreate(
                ['id' => $request['income_id']],
                [
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

    public function getMonthlyTotal()
    {
        $startDate = now()->subDays(30);
        $endDate = now();

        $incomeTotal = DB::table('incomes')
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json(['incomeTotal' => $incomeTotal]);
    }

   

    public function getWeeklyTotal()
    {
        $startDate = now()->subDays(7);
        $endDate = now();

        $incomeWeeklyTotal = DB::table('incomes')
            ->whereNull('deleted_at')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return response()->json(['incomeWeeklyTotal' => $incomeWeeklyTotal]);
    }

    public function getDailyTotal()
    {
        $timezone = 'Asia/Manila';
        $dateToday = Carbon::now($timezone);

        $incomeDailyTotal = DB::table('incomes')
            ->whereNull('deleted_at')
            ->where('date', $dateToday->toDateString())
            ->sum('amount');

        return response()->json(['incomeDailyTotal' => $incomeDailyTotal]);
    }
}
