<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Http\Requests\StoreExpenseRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('expense_id', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%");
            });
        }

        if ($from = $request->get('from')) {
            $query->whereDate('date', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->whereDate('date', '<=', $to);
        }

        // Period totals
        $today       = Carbon::today();
        $dailyTotal  = Expense::whereDate('date', $today)->sum('amount');
        $weeklyTotal = Expense::whereBetween('date', [
            $today->copy()->startOfWeek(), $today->copy()->endOfWeek()
        ])->sum('amount');
        $monthlyTotal = Expense::whereMonth('date', $today->month)
            ->whereYear('date', $today->year)->sum('amount');
        $yearlyTotal  = Expense::whereYear('date', $today->year)->sum('amount');

        $expenses = $query->orderByDesc('date')->paginate(15)->withQueryString();

        $totalExpenses = $query->sum('amount');

        return view('expenses.index', compact(
            'expenses',
            'dailyTotal',
            'weeklyTotal',
            'monthlyTotal',
            'yearlyTotal',
            'totalExpenses'
        ));
    }

    public function create()
    {
        $nextId = Expense::generateExpenseId();
        return view('expenses.create', compact('nextId'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $data = $request->validated();
        $data['expense_id'] = Expense::generateExpenseId();
        Expense::create($data);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(StoreExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());
        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
