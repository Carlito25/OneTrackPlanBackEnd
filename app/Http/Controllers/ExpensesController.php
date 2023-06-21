<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenses;
use App\Http\Requests\ExpensesRequest;

class ExpensesController extends Controller
{
    public function index(){
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
}
