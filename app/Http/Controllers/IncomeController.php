<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Http\Requests\IncomeRequest;

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
                return response()->json();
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function show(Income $income){
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
}
