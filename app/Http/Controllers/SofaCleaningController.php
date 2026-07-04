<?php

namespace App\Http\Controllers;

use App\Models\SofaCleaning;
use App\Http\Requests\StoreSofaCleaningRequest;
use Illuminate\Http\Request;

class SofaCleaningController extends Controller
{
    public function index(Request $request)
    {
        $query = SofaCleaning::query();

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

        return view('sofa-cleaning.index', compact('records', 'totalRevenue'));
    }

    public function create()
    {
        $nextId = SofaCleaning::generateRecordId();
        return view('sofa-cleaning.create', compact('nextId'));
    }

    public function store(StoreSofaCleaningRequest $request)
    {
        $data = $request->validated();
        $data['record_id'] = SofaCleaning::generateRecordId();
        SofaCleaning::create($data);

        return redirect()->route('sofa-cleaning.index')
            ->with('success', 'Sofa cleaning record added successfully.');
    }

    public function show(SofaCleaning $sofaCleaning)
    {
        return view('sofa-cleaning.show', compact('sofaCleaning'));
    }

    public function edit(SofaCleaning $sofaCleaning)
    {
        return view('sofa-cleaning.edit', compact('sofaCleaning'));
    }

    public function update(StoreSofaCleaningRequest $request, SofaCleaning $sofaCleaning)
    {
        $sofaCleaning->update($request->validated());
        return redirect()->route('sofa-cleaning.index')
            ->with('success', 'Sofa cleaning record updated successfully.');
    }

    public function destroy(SofaCleaning $sofaCleaning)
    {
        $sofaCleaning->delete();
        return redirect()->route('sofa-cleaning.index')
            ->with('success', 'Sofa cleaning record deleted successfully.');
    }
}
