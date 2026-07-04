<?php

namespace App\Http\Controllers;

use App\Models\SolarClient;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\SofaCleaning;
use App\Models\DeepCleaning;
use App\Models\Expense;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Summary stats
        $totalSolarClients  = SolarClient::count();
        $totalLeads         = Lead::count();
        $today = now()->toDateString();
        $totalSiteVisits    = SolarClient::query()
            ->whereNotNull('first_visit_date')
            ->orWhereNotNull('second_visit_date')
            ->orWhereNotNull('third_visit_date')
            ->orWhereNotNull('fourth_visit_date')
            ->count();

        // Revenue = sum of all amount_received / amounts across modules
        $solarRevenue    = SolarClient::selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total') ?? 0;
        $leadRevenue     = Lead::sum('amount_received') ?? 0;
        $sofaRevenue     = SofaCleaning::sum('amount_received') ?? 0;
        $deepRevenue     = DeepCleaning::sum('amount_received') ?? 0;
        $totalRevenue    = $solarRevenue + $leadRevenue + $sofaRevenue + $deepRevenue;

        $todaysLeads = Lead::whereDate('date', $today)->count();
        $todaysRevenue = (SolarClient::whereDate('created_at', $today)->selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total') ?? 0)
            + (Lead::whereDate('created_at', $today)->sum('amount_received') ?? 0)
            + (SofaCleaning::whereDate('created_at', $today)->sum('amount_received') ?? 0)
            + (DeepCleaning::whereDate('created_at', $today)->sum('amount_received') ?? 0);

        // Expenses
        $totalExpenses   = Expense::sum('amount') ?? 0;

        // Pending reminders (solar clients with reminder_date <= today and not sent)
        $pendingReminders = SolarClient::pendingReminders()
            ->orderBy('reminder_date')
            ->get();

        $pendingFollowUps = FollowUp::pending()
            ->whereDate('due_date', '<=', $today)
            ->with('solarClient')
            ->orderBy('due_date')
            ->get();

        $upcomingVisits = SolarClient::query()
            ->where(function ($query) use ($today) {
                $query->whereDate('first_visit_date', '>=', $today)
                    ->orWhereDate('second_visit_date', '>=', $today)
                    ->orWhereDate('third_visit_date', '>=', $today)
                    ->orWhereDate('fourth_visit_date', '>=', $today);
            })
            ->latest()
            ->limit(8)
            ->get();

        $whatsapp = new WhatsAppService();

        // Recent activities (latest records from all modules combined — using union approach)
        $recentSolarClients = SolarClient::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Solar Client',
            'id'      => $r->client_id,
            'name'    => $r->name,
            'contact' => $r->contact,
            'date'    => $r->created_at,
            'badge'   => 'solar-badge',
        ]);
        $recentLeads = Lead::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Lead',
            'id'      => $r->lead_id,
            'name'    => $r->name,
            'contact' => $r->contact,
            'date'    => $r->created_at,
            'badge'   => 'lead-badge',
        ]);
        $recentSofa = SofaCleaning::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Sofa Cleaning',
            'id'      => $r->record_id,
            'name'    => $r->customer_name,
            'contact' => $r->contact,
            'date'    => $r->created_at,
            'badge'   => 'sofa-badge',
        ]);
        $recentDeep = DeepCleaning::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Deep Cleaning',
            'id'      => $r->record_id,
            'name'    => $r->customer_name,
            'contact' => $r->contact,
            'date'    => $r->created_at,
            'badge'   => 'deep-badge',
        ]);

        $recentActivities = $recentSolarClients
            ->merge($recentLeads)
            ->merge($recentSofa)
            ->merge($recentDeep)
            ->sortByDesc('date')
            ->take(10)
            ->values();

        // Latest customers (latest solar clients)
        $latestCustomers = SolarClient::latest()->limit(8)->get();

        return view('dashboard.index', compact(
            'totalSolarClients',
            'totalLeads',
            'totalSiteVisits',
            'totalRevenue',
            'totalExpenses',
            'todaysLeads',
            'todaysRevenue',
            'pendingReminders',
            'pendingFollowUps',
            'upcomingVisits',
            'recentActivities',
            'latestCustomers',
            'whatsapp'
        ));
    }

    public function globalSearch(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (empty($query)) {
            return redirect()->route('dashboard');
        }

        $solarClients = SolarClient::where('client_id', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('contact', 'LIKE', "%{$query}%")
            ->limit(10)->get();

        $leads = Lead::where('lead_id', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('contact', 'LIKE', "%{$query}%")
            ->limit(10)->get();

        $sofaCleanings = SofaCleaning::where('record_id', 'LIKE', "%{$query}%")
            ->orWhere('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('contact', 'LIKE', "%{$query}%")
            ->limit(10)->get();

        $deepCleanings = DeepCleaning::where('record_id', 'LIKE', "%{$query}%")
            ->orWhere('customer_name', 'LIKE', "%{$query}%")
            ->orWhere('contact', 'LIKE', "%{$query}%")
            ->limit(10)->get();

        return view('dashboard.search', compact(
            'query',
            'solarClients',
            'leads',
            'sofaCleanings',
            'deepCleanings'
        ));
    }

    public function dismissReminder(SolarClient $solarClient)
    {
        $solarClient->update(['reminder_sent' => true]);
        $solarClient->followUps()
            ->pending()
            ->whereDate('due_date', $solarClient->reminder_date)
            ->update(['status' => 'completed', 'completed_at' => now()]);

        return back()->with('success', 'Reminder dismissed for ' . $solarClient->name);
    }
}
