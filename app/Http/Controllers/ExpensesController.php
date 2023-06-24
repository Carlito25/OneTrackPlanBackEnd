<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenses;
use App\Http\Requests\ExpensesRequest;

class ExpensesController extends Controller
{
    public function index()
    {
        $expenses = Expenses::get();

        return response()->json($expenses);
    }

    public function store(ExpensesRequest $request)
    {
        try {
            $exp = Expenses::updateOrCreate(
                ['id' => $request['expenses_id']],
                [
                    'date' => $request['date'],
                    'expenses' => $request['expenses'],
                    'amount' => $request['amount'],
                ]
            );
            if ($exp->wasRecentlyCreated) {
                return response()->json();
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    // public function show($id)
    // {
    //     $expense = Expenses::find($id);
    //     dd($expense);

    //     return response()->json($expense);
    // }
    public function show(Expenses $expense){
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
}
