<?php

namespace App\Http\Controllers;

use App\Models\SolarSite;
use App\Http\Requests\StoreSolarSiteRequest;
use Illuminate\Http\Request;

class SolarSiteController extends Controller
{
    public function index(Request $request)
    {
        $query = SolarSite::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('site_id', 'LIKE', "%{$search}%")
                  ->orWhere('client_name', 'LIKE', "%{$search}%")
                  ->orWhere('contact', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($date = $request->get('date')) {
            $query->whereDate('visit_date', $date);
        }

        $totalCharges = (clone $query)->sum('charges');
        $sites = $query->latest()->paginate(15)->withQueryString();

        return view('solar-sites.index', compact('sites', 'totalCharges'));
    }

    public function create()
    {
        $nextId = SolarSite::generateSiteId();
        return view('solar-sites.create', compact('nextId'));
    }

    public function store(StoreSolarSiteRequest $request)
    {
        $data = $request->validated();
        $data['site_id'] = SolarSite::generateSiteId();
        SolarSite::create($data);

        return redirect()->route('solar-sites.index')
            ->with('success', 'Solar site record added successfully.');
    }

    public function show(SolarSite $solarSite)
    {
        return view('solar-sites.show', compact('solarSite'));
    }

    public function edit(SolarSite $solarSite)
    {
        return view('solar-sites.edit', compact('solarSite'));
    }

    public function update(StoreSolarSiteRequest $request, SolarSite $solarSite)
    {
        $solarSite->update($request->validated());
        return redirect()->route('solar-sites.index')
            ->with('success', 'Solar site record updated successfully.');
    }

    public function destroy(SolarSite $solarSite)
    {
        $solarSite->delete();
        return redirect()->route('solar-sites.index')
            ->with('success', 'Solar site record deleted successfully.');
    }
}
