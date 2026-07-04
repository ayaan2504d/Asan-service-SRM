<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\SolarClient;
use App\Http\Requests\StoreSolarClientRequest;
use App\Http\Requests\UpdateSolarClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SolarClientController extends Controller
{
    public function index(Request $request)
    {
        $query = SolarClient::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('client_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('contact', 'LIKE', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('solar-clients.index', compact('clients'));
    }

    public function create()
    {
        $nextId = SolarClient::generateClientId();
        return view('solar-clients.create', compact('nextId'));
    }

    public function store(StoreSolarClientRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = SolarClient::generateClientId();

        $data = $this->applyReminderDate($data);

        $solarClient = SolarClient::create($data);
        $this->syncFollowUp($solarClient);

        return redirect()->route('solar-clients.index')
            ->with('success', 'Solar client added successfully.');
    }

    public function show(SolarClient $solarClient)
    {
        return view('solar-clients.show', compact('solarClient'));
    }

    public function edit(SolarClient $solarClient)
    {
        return view('solar-clients.edit', compact('solarClient'));
    }

    public function update(UpdateSolarClientRequest $request, SolarClient $solarClient)
    {
        $data = $request->validated();

        $oldReminderDate = $solarClient->reminder_date?->toDateString();
        $data = $this->applyReminderDate($data);

        if (($data['reminder_date'] ?? null) !== $oldReminderDate) {
            $data['reminder_sent'] = false;
        }

        $solarClient->update($data);
        $this->syncFollowUp($solarClient->fresh());

        return redirect()->route('solar-clients.index')
            ->with('success', 'Solar client updated successfully.');
    }

    public function destroy(SolarClient $solarClient)
    {
        $solarClient->delete();
        return redirect()->route('solar-clients.index')
            ->with('success', 'Solar client deleted successfully.');
    }

    private function applyReminderDate(array $data): array
    {
        $latestVisit = collect([
            $data['first_visit_date'] ?? null,
            $data['second_visit_date'] ?? null,
            $data['third_visit_date'] ?? null,
            $data['fourth_visit_date'] ?? null,
        ])->filter()->map(fn ($date) => Carbon::parse($date))->sort()->last();

        $data['reminder_date'] = $latestVisit?->copy()->addDays(13)->toDateString();

        return $data;
    }

    private function syncFollowUp(SolarClient $solarClient): void
    {
        if (!$solarClient->reminder_date) {
            return;
        }

        FollowUp::firstOrCreate([
            'solar_client_id' => $solarClient->id,
            'due_date' => $solarClient->reminder_date->toDateString(),
            'source' => 'solar_latest_visit',
        ]);
    }
}
