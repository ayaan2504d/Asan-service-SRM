<?php

namespace App\Http\Controllers;

use App\Models\DeepCleaning;
use App\Http\Requests\StoreDeepCleaningRequest;
use Illuminate\Http\Request;

class DeepCleaningController extends Controller
{
    public function index(Request $request)
    {
        $query = DeepCleaning::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('record_id', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('contact', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $totalRevenue = (clone $query)->sum('amount_received');
        $records = $query->latest()->paginate(15)->withQueryString();

        return view('deep-cleaning.index', compact('records', 'totalRevenue'));
    }

    public function create()
    {
        $nextId = DeepCleaning::generateRecordId();
        return view('deep-cleaning.create', compact('nextId'));
    }

    public function store(StoreDeepCleaningRequest $request)
    {
        $data = $request->validated();
        $data['record_id'] = DeepCleaning::generateRecordId();
        DeepCleaning::create($data);

        return redirect()->route('deep-cleaning.index')
            ->with('success', 'Deep cleaning record added successfully.');
    }

    public function show(DeepCleaning $deepCleaning)
    {
        return view('deep-cleaning.show', compact('deepCleaning'));
    }

    public function edit(DeepCleaning $deepCleaning)
    {
        return view('deep-cleaning.edit', compact('deepCleaning'));
    }

    public function update(StoreDeepCleaningRequest $request, DeepCleaning $deepCleaning)
    {
        $deepCleaning->update($request->validated());
        return redirect()->route('deep-cleaning.index')
            ->with('success', 'Deep cleaning record updated successfully.');
    }

    public function destroy(DeepCleaning $deepCleaning)
    {
        $deepCleaning->delete();
        return redirect()->route('deep-cleaning.index')
            ->with('success', 'Deep cleaning record deleted successfully.');
    }
}
