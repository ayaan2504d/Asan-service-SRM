<?php

namespace App\Http\Controllers;

use App\Models\SolarClient;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\SofaCleaning;
use App\Models\DeepCleaning;
use App\Models\Expense;
use App\Models\ServiceReminder;
use App\Models\CustomerVisit;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ════════════════ 0. DYNAMIC DATE & TIMEZONE SYSTEM ════════════════
        $now = Carbon::now('Asia/Karachi');
        $today = $now->toDateString();

        $todayDateFormatted     = $now->format('l, d F Y'); // e.g. Monday, 27 July 2026
        $currentMonthFormatted  = $now->format('F Y');      // e.g. July 2026
        $currentYearFormatted   = $now->format('Y');        // e.g. 2026

        // Calendar Month Navigation Parameters
        $month = (int) $request->get('month', $now->month);
        $year  = (int) $request->get('year', $now->year);

        // Clamp month/year bounds securely
        if ($month < 1) { $month = 12; $year--; }
        if ($month > 12) { $month = 1; $year++; }

        $selectedMonthDate = Carbon::createFromDate($year, $month, 1, 'Asia/Karachi');
        $prevMonthDate     = (clone $selectedMonthDate)->subMonth();
        $nextMonthDate     = (clone $selectedMonthDate)->addMonth();

        $calendarMonthTitle = $selectedMonthDate->format('F Y');
        $daysInMonth        = $selectedMonthDate->daysInMonth;
        $startDayOfWeek     = $selectedMonthDate->copy()->startOfMonth()->dayOfWeek; // 0 (Sun) to 6 (Sat)

        // Build Calendar Grid Events
        $calendarGrid = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = Carbon::createFromDate($year, $month, $day, 'Asia/Karachi')->toDateString();
            $calendarGrid[$day] = [
                'date'       => $dateString,
                'is_today'   => ($dateString === $today),
                'reminders'  => ServiceReminder::whereDate('next_reminder_date', $dateString)->get(),
                'visits'     => CustomerVisit::whereDate('visit_date', $dateString)->get(),
                'sofa_jobs'  => SofaCleaning::whereDate('service_date', $dateString)->get(),
                'deep_jobs'  => DeepCleaning::whereDate('service_date', $dateString)->get(),
                'solar_jobs' => SolarClient::whereDate('first_visit_date', $dateString)->orWhereDate('second_visit_date', $dateString)->get(),
            ];
        }

        // ════════════════ 1. MAIN FINANCIAL SUMMARY ════════════════
        $solarEarnings = (float) SolarClient::selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total');
        $sofaEarnings  = (float) (SofaCleaning::sum('amount_received') ?: SofaCleaning::sum('service_charges'));
        $deepEarnings  = (float) (DeepCleaning::sum('amount_received') ?: DeepCleaning::sum('service_charges'));
        $leadEarnings  = (float) Lead::sum('amount_received');

        $totalEarnings = $solarEarnings + $sofaEarnings + $deepEarnings + $leadEarnings;
        $totalExpenses = (float) Expense::sum('amount');
        $totalProfit   = $totalEarnings - $totalExpenses;

        // Customer & Job Counts
        $allContacts = collect()
            ->merge(SolarClient::pluck('contact'))
            ->merge(SofaCleaning::pluck('contact'))
            ->merge(DeepCleaning::pluck('contact'))
            ->merge(Lead::pluck('contact'))
            ->filter()
            ->unique();
        $totalCustomers = $allContacts->count();

        $completedSolarServices = SolarClient::whereNotNull('first_visit_date')->count();
        $completedSofaServices  = SofaCleaning::whereIn('status', ['Done', 'Completed', 'done', 'completed'])->count();
        $completedDeepServices  = DeepCleaning::whereIn('status', ['Done', 'Completed', 'done', 'completed'])->count();
        $totalCompletedServices = $completedSolarServices + $completedSofaServices + $completedDeepServices;

        $pendingSofaServices = SofaCleaning::whereIn('status', ['Pending', 'pending'])->count();
        $pendingDeepServices = DeepCleaning::whereIn('status', ['Pending', 'pending'])->count();
        $pendingLeads        = Lead::where('status', 'Pending')->count();
        $pendingServices     = $pendingSofaServices + $pendingDeepServices + $pendingLeads;

        // ════════════════ 1b. MARKETING LEADS KPI (NEW) ════════════════
        $totalLeads   = Lead::count();
        $followUpLeads = Lead::where('status', 'Follow Up')->count();
        $doneLeads    = Lead::where('status', 'Done')->count();
        $cancelLeads  = Lead::where('status', 'Cancel')->count();

        // Leads By Status Chart Data
        $leadsStatusChart = [
            'labels' => ['Pending', 'Follow Up', 'Done', 'Cancel'],
            'data'   => [$pendingLeads, $followUpLeads, $doneLeads, $cancelLeads],
            'colors' => ['#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b'],
        ];

        // Leads By Service Type Chart Data
        $solarLeadsCount = Lead::where(function($q) { $q->where('service_type', 'Solar Cleaning')->orWhere('type_of_service', 'Solar Cleaning'); })->count();
        $sofaLeadsCount  = Lead::where(function($q) { $q->where('service_type', 'Sofa Cleaning')->orWhere('type_of_service', 'Sofa Cleaning'); })->count();
        $deepLeadsCount  = Lead::where(function($q) { $q->where('service_type', 'Deep Cleaning')->orWhere('type_of_service', 'Deep Cleaning'); })->count();

        $leadsServiceChart = [
            'labels' => ['Solar Cleaning', 'Sofa Cleaning', 'Deep Cleaning'],
            'data'   => [$solarLeadsCount, $sofaLeadsCount, $deepLeadsCount],
            'colors' => ['#f6c23e', '#36b9cc', '#4e73df'],
        ];

        // Campaign Performance Chart Data
        $campaignData = Lead::whereNotNull('campaign_name')
            ->where('campaign_name', '!=', '')
            ->selectRaw('campaign_name, COUNT(*) as total, SUM(CASE WHEN status = "Done" THEN 1 ELSE 0 END) as done, SUM(CASE WHEN status = "Cancel" THEN 1 ELSE 0 END) as cancel')
            ->groupBy('campaign_name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $campaignPerformanceChart = [
            'labels'  => $campaignData->pluck('campaign_name')->toArray(),
            'total'   => $campaignData->pluck('total')->toArray(),
            'done'    => $campaignData->pluck('done')->toArray(),
            'cancel'  => $campaignData->pluck('cancel')->toArray(),
        ];

        // ════════════════ 2. SERVICE WISE EARNING ANALYTICS ════════════════
        $serviceAnalytics = [
            'solar' => [
                'total_customers'    => SolarClient::count(),
                'completed_services' => $completedSolarServices,
                'total_earnings'     => $solarEarnings,
            ],
            'sofa' => [
                'total_customers'    => SofaCleaning::count(),
                'completed_services' => $completedSofaServices,
                'total_earnings'     => $sofaEarnings,
            ],
            'deep' => [
                'total_customers'    => DeepCleaning::count(),
                'completed_services' => $completedDeepServices,
                'total_earnings'     => $deepEarnings,
            ],
        ];

        // ════════════════ 3. EXPENSE MANAGEMENT INTEGRATION ════════════════
        $todayExpense     = (float) Expense::whereDate('date', $today)->sum('amount');
        $thisMonthExpense = (float) Expense::whereYear('date', $now->year)->whereMonth('date', $now->month)->sum('amount');

        $expenseCategories = Expense::query()
            ->selectRaw('COALESCE(category, title) as cat_name, SUM(amount) as total_amount')
            ->groupBy('cat_name')
            ->orderByDesc('total_amount')
            ->get();

        // ════════════════ 4. SOLAR CLEANING 14-DAY REMINDER SYSTEM ════════════════
        $this->syncSolar14DayReminders();

        $due14DayReminders = ServiceReminder::due()
            ->where('reminder_type', '14_days')
            ->orderBy('next_reminder_date')
            ->get();

        // ════════════════ 5. MONTHLY SERVICE REMINDER (2 VISITS PER MONTH) ════════════════
        $this->syncMonthlyVisitsForYearMonth($year, $month);

        $upcomingMonthlyVisits = CustomerVisit::upcoming()
            ->whereYear('visit_date', $year)
            ->whereMonth('visit_date', $month)
            ->orderBy('visit_date')
            ->get();

        $upcomingRemindersCount = $due14DayReminders->count() + SolarClient::pendingReminders()->count();

        // ════════════════ 6. CUSTOMER FOLLOW UP PANEL ════════════════
        $solarFollowUps = SolarClient::pendingReminders()->limit(5)->get()->map(fn($c) => [
            'type'        => 'Solar Client',
            'name'        => $c->name,
            'phone'       => $c->contact,
            'whatsapp'    => $c->whatsapp_number ?: $c->contact,
            'due_date'    => $c->reminder_date,
            'url'         => route('solar-clients.show', $c->id),
        ]);

        $sofaFollowUps = SofaCleaning::whereIn('status', ['Pending', 'Follow Up', 'pending'])->limit(5)->get()->map(fn($s) => [
            'type'        => 'Sofa Cleaning',
            'name'        => $s->customer_name,
            'phone'       => $s->contact,
            'whatsapp'    => $s->whatsapp_number ?: $s->contact,
            'due_date'    => $s->service_date,
            'url'         => route('sofa-cleaning.show', $s->id),
        ]);

        $deepFollowUps = DeepCleaning::whereIn('status', ['Pending', 'Follow Up', 'pending'])->limit(5)->get()->map(fn($d) => [
            'type'        => 'Deep Cleaning',
            'name'        => $d->customer_name,
            'phone'       => $d->contact,
            'whatsapp'    => $d->whatsapp_number ?: $d->contact,
            'due_date'    => $d->service_date,
            'url'         => route('deep-cleaning.show', $d->id),
        ]);

        $customerFollowUps = $solarFollowUps->merge($sofaFollowUps)->merge($deepFollowUps)->take(10);

        // ════════════════ 7. BUSINESS KPI SECTION (THIS MONTH) ════════════════
        $thisMonthSolarJobs = SolarClient::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->count();
        $thisMonthSofaJobs  = SofaCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->count();
        $thisMonthDeepJobs  = DeepCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->count();
        $thisMonthJobs      = $thisMonthSolarJobs + $thisMonthSofaJobs + $thisMonthDeepJobs;

        $thisMonthCompletedJobs = SofaCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->whereIn('status', ['Done', 'Completed'])->count()
            + DeepCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->whereIn('status', ['Done', 'Completed'])->count()
            + SolarClient::whereYear('first_visit_date', $now->year)->whereMonth('first_visit_date', $now->month)->count();

        $thisMonthCancelledJobs = SofaCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->whereIn('status', ['Cancel', 'Cancelled'])->count()
            + DeepCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->whereIn('status', ['Cancel', 'Cancelled'])->count()
            + Lead::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->where('status', 'Cancel')->count();

        $thisMonthIncome = (float) (SolarClient::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total'))
            + (float) (SofaCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->sum('amount_received'))
            + (float) (DeepCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->sum('amount_received'))
            + (float) (Lead::whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->sum('amount_received'));

        $thisMonthNetProfit = $thisMonthIncome - $thisMonthExpense;

        $monthlyKpis = [
            'total_jobs'     => $thisMonthJobs,
            'completed_jobs' => $thisMonthCompletedJobs,
            'cancelled_jobs' => $thisMonthCancelledJobs,
            'total_income'   => $thisMonthIncome,
            'total_expense'  => $thisMonthExpense,
            'net_profit'     => $thisMonthNetProfit,
        ];

        // ════════════════ 8. PRO FEATURES (CLV, TOP CUSTOMERS, DAILY SNAPSHOT, RETENTION) ════════════════
        $dailySnapshot = [
            'leads_today'     => Lead::whereDate('created_at', $today)->count(),
            'bookings_today'  => SofaCleaning::whereDate('created_at', $today)->count() + DeepCleaning::whereDate('created_at', $today)->count() + SolarClient::whereDate('created_at', $today)->count(),
            'earnings_today'  => (float) (SolarClient::whereDate('created_at', $today)->selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total'))
                + (float) SofaCleaning::whereDate('created_at', $today)->sum('amount_received')
                + (float) DeepCleaning::whereDate('created_at', $today)->sum('amount_received')
                + (float) Lead::whereDate('created_at', $today)->sum('amount_received'),
            'reminders_today' => $upcomingRemindersCount,
        ];

        // Top Customers & CLV
        $customerClv = collect();
        foreach (SolarClient::all() as $c) {
            $key = $c->contact ?: $c->name;
            if (!$key) continue;
            if (!$customerClv->has($key)) {
                $customerClv->put($key, ['name' => $c->name, 'contact' => $c->contact, 'total_spent' => 0, 'visits' => 0, 'last_date' => null, 'next_reminder' => $c->reminder_date]);
            }
            $item = $customerClv->get($key);
            $item['total_spent'] += $c->total_amount;
            $item['visits'] += ($c->first_visit_date ? 1 : 0) + ($c->second_visit_date ? 1 : 0);
            $item['last_date'] = $c->latestVisitDate() ?: $item['last_date'];
            $customerClv->put($key, $item);
        }

        foreach (SofaCleaning::all() as $s) {
            $key = $s->contact ?: $s->customer_name;
            if (!$key) continue;
            if (!$customerClv->has($key)) {
                $customerClv->put($key, ['name' => $s->customer_name, 'contact' => $s->contact, 'total_spent' => 0, 'visits' => 0, 'last_date' => null, 'next_reminder' => null]);
            }
            $item = $customerClv->get($key);
            $item['total_spent'] += (float) ($s->amount_received ?: $s->service_charges);
            $item['visits'] += 1;
            $item['last_date'] = $s->service_date ?: $item['last_date'];
            $customerClv->put($key, $item);
        }

        foreach (DeepCleaning::all() as $d) {
            $key = $d->contact ?: $d->customer_name;
            if (!$key) continue;
            if (!$customerClv->has($key)) {
                $customerClv->put($key, ['name' => $d->customer_name, 'contact' => $d->contact, 'total_spent' => 0, 'visits' => 0, 'last_date' => null, 'next_reminder' => null]);
            }
            $item = $customerClv->get($key);
            $item['total_spent'] += (float) ($d->amount_received ?: $d->service_charges);
            $item['visits'] += 1;
            $item['last_date'] = $d->service_date ?: $item['last_date'];
            $customerClv->put($key, $item);
        }

        $topCustomers = $customerClv->sortByDesc('total_spent')->take(5)->values();
        $returningCustomers = $customerClv->filter(fn($c) => $c['visits'] > 1)->sortByDesc('visits')->take(8)->values();

        // ════════════════ 9. DASHBOARD CHARTS DATA ════════════════
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyEarningsData = [];
        $incomeVsExpenseData = ['income' => [], 'expense' => []];

        for ($m = 1; $m <= 12; $m++) {
            $solarM = (float) SolarClient::whereYear('created_at', $now->year)->whereMonth('created_at', $m)->selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total');
            $sofaM  = (float) SofaCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $m)->sum('amount_received');
            $deepM  = (float) DeepCleaning::whereYear('created_at', $now->year)->whereMonth('created_at', $m)->sum('amount_received');
            $leadM  = (float) Lead::whereYear('created_at', $now->year)->whereMonth('created_at', $m)->sum('amount_received');

            $monthTotal = $solarM + $sofaM + $deepM + $leadM;
            $monthlyEarningsData[] = $monthTotal;

            $expenseM = (float) Expense::whereYear('date', $now->year)->whereMonth('date', $m)->sum('amount');
            $incomeVsExpenseData['income'][]  = $monthTotal;
            $incomeVsExpenseData['expense'][] = $expenseM;
        }

        $servicePerformanceData = [
            'Solar Cleaning' => $solarEarnings,
            'Sofa Cleaning'  => $sofaEarnings,
            'Deep Cleaning'  => $deepEarnings,
        ];

        $customerGrowthData = [];
        $growthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $growthLabels[] = $date->format('M Y');
            $cumulativeCount = SolarClient::whereDate('created_at', '<=', $date->endOfMonth()->toDateString())->count()
                + SofaCleaning::whereDate('created_at', '<=', $date->endOfMonth()->toDateString())->count()
                + DeepCleaning::whereDate('created_at', '<=', $date->endOfMonth()->toDateString())->count();
            $customerGrowthData[] = $cumulativeCount;
        }

        // ════════════════ 10. RECENT ACTIVITIES ════════════════
        $recentSolarClients = SolarClient::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'New Solar Customer Added',
            'id'      => $r->client_id,
            'name'    => $r->name,
            'date'    => $r->created_at,
            'badge'   => 'bg-warning text-dark',
        ]);

        $recentSofa = SofaCleaning::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Sofa Cleaning Service',
            'id'      => $r->record_id,
            'name'    => $r->customer_name,
            'date'    => $r->created_at,
            'badge'   => 'bg-info text-white',
        ]);

        $recentDeep = DeepCleaning::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Deep Cleaning Service',
            'id'      => $r->record_id,
            'name'    => $r->customer_name,
            'date'    => $r->created_at,
            'badge'   => 'bg-primary',
        ]);

        $recentExpenses = Expense::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Expense Added (' . ($r->category ?: $r->title) . ')',
            'id'      => $r->expense_id,
            'name'    => '₨ ' . number_format($r->amount, 0),
            'date'    => $r->created_at,
            'badge'   => 'bg-danger text-white',
        ]);

        $recentLeads = Lead::latest()->limit(5)->get()->map(fn($r) => [
            'type'    => 'Marketing Lead Captured',
            'id'      => $r->lead_id,
            'name'    => $r->name,
            'date'    => $r->created_at,
            'badge'   => 'bg-purple text-white',
        ]);

        $recentActivities = $recentSolarClients
            ->merge($recentSofa)
            ->merge($recentDeep)
            ->merge($recentExpenses)
            ->merge($recentLeads)
            ->sortByDesc('date')
            ->take(10)
            ->values();

        $whatsapp = new WhatsAppService();

        return view('dashboard.index', compact(
            'todayDateFormatted',
            'currentMonthFormatted',
            'currentYearFormatted',
            'month',
            'year',
            'prevMonthDate',
            'nextMonthDate',
            'calendarMonthTitle',
            'daysInMonth',
            'startDayOfWeek',
            'calendarGrid',
            'totalEarnings',
            'totalExpenses',
            'totalProfit',
            'totalCustomers',
            'totalCompletedServices',
            'pendingServices',
            'upcomingRemindersCount',
            'serviceAnalytics',
            'todayExpense',
            'thisMonthExpense',
            'expenseCategories',
            'due14DayReminders',
            'upcomingMonthlyVisits',
            'customerFollowUps',
            'monthlyKpis',
            'dailySnapshot',
            'topCustomers',
            'returningCustomers',
            'months',
            'monthlyEarningsData',
            'servicePerformanceData',
            'incomeVsExpenseData',
            'growthLabels',
            'customerGrowthData',
            'recentActivities',
            'whatsapp',
            // Lead KPIs (new)
            'totalLeads',
            'pendingLeads',
            'followUpLeads',
            'doneLeads',
            'cancelLeads',
            'leadsStatusChart',
            'leadsServiceChart',
            'campaignPerformanceChart'
        ));
    }

    public function exportBusinessPdf(Request $request)
    {
        $now = Carbon::now('Asia/Karachi');

        $solarEarnings = (float) SolarClient::selectRaw('COALESCE(SUM(amount),0) + COALESCE(SUM(second_amount),0) as total')->value('total');
        $sofaEarnings  = (float) (SofaCleaning::sum('amount_received') ?: SofaCleaning::sum('service_charges'));
        $deepEarnings  = (float) (DeepCleaning::sum('amount_received') ?: DeepCleaning::sum('service_charges'));
        $leadEarnings  = (float) Lead::sum('amount_received');

        $totalEarnings = $solarEarnings + $sofaEarnings + $deepEarnings + $leadEarnings;
        $totalExpenses = (float) Expense::sum('amount');
        $totalProfit   = $totalEarnings - $totalExpenses;

        $due14DayReminders = ServiceReminder::due()->limit(10)->get();

        $pdfData = [
            'companyName'        => 'Asan Home Service',
            'generatedAt'        => $now->format('Y-m-d H:i:s T'),
            'totalEarnings'      => $totalEarnings,
            'totalExpenses'      => $totalExpenses,
            'totalProfit'        => $totalProfit,
            'solarEarnings'      => $solarEarnings,
            'sofaEarnings'       => $sofaEarnings,
            'deepEarnings'       => $deepEarnings,
            'due14DayReminders'  => $due14DayReminders,
        ];

        $pdf = Pdf::loadView('dashboard.pdf', $pdfData);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'business_dashboard_report_' . $now->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function completeReminder(ServiceReminder $reminder)
    {
        $reminder->update(['status' => 'Completed']);

        // Schedule next 14 day reminder automatically
        $lastDate = $reminder->last_service_date ? Carbon::parse($reminder->last_service_date) : Carbon::now('Asia/Karachi');
        $nextDate = $lastDate->copy()->addDays(14)->toDateString();

        ServiceReminder::create([
            'customer_id'       => $reminder->customer_id,
            'customer_name'     => $reminder->customer_name,
            'contact'           => $reminder->contact,
            'service_type'      => $reminder->service_type,
            'last_service_date' => $lastDate->toDateString(),
            'next_reminder_date'=> $nextDate,
            'status'            => 'Pending',
            'reminder_type'     => '14_days',
            'notes'             => 'Auto-scheduled post completed visit.',
        ]);

        return back()->with('success', "Service reminder completed for {$reminder->customer_name}. Next 14-day reminder scheduled for {$nextDate}!");
    }

    private function syncSolar14DayReminders(): void
    {
        $solarClients = SolarClient::whereNotNull('first_visit_date')->get();

        foreach ($solarClients as $sc) {
            $lastVisit = $sc->latestVisitDate();
            if (!$lastVisit) continue;

            // Service Date + 14 Days
            $nextDueDate = Carbon::parse($lastVisit)->addDays(14)->toDateString();

            $exists = ServiceReminder::where('customer_id', $sc->client_id)
                ->where('reminder_type', '14_days')
                ->where('status', 'Pending')
                ->exists();

            if (!$exists) {
                ServiceReminder::create([
                    'customer_id'       => $sc->client_id,
                    'customer_name'     => $sc->name,
                    'contact'           => $sc->contact,
                    'service_type'      => 'Solar Cleaning',
                    'last_service_date' => $lastVisit->toDateString(),
                    'next_reminder_date'=> $nextDueDate,
                    'status'            => 'Pending',
                    'reminder_type'     => '14_days',
                    'notes'             => '14 Days Solar Service Reminder',
                ]);
            }
        }
    }

    private function syncMonthlyVisitsForYearMonth(int $year, int $month): void
    {
        $solarClients = SolarClient::whereNotNull('first_visit_date')->get();

        foreach ($solarClients as $sc) {
            // Auto generate Visit 1 & Visit 2 for 2-visit/month clients in target month
            $visit1Date = Carbon::createFromDate($year, $month, 5, 'Asia/Karachi')->toDateString();
            $visit2Date = Carbon::createFromDate($year, $month, 20, 'Asia/Karachi')->toDateString();

            // Visit 1 Sync
            $exists1 = CustomerVisit::where('customer_id', $sc->client_id)
                ->whereYear('visit_date', $year)
                ->whereMonth('visit_date', $month)
                ->where('visit_number', 1)
                ->exists();

            if (!$exists1) {
                CustomerVisit::create([
                    'customer_id'   => $sc->client_id,
                    'customer_name' => $sc->name,
                    'contact'       => $sc->contact,
                    'service_type'  => 'Solar Cleaning',
                    'visit_number'  => 1,
                    'visit_date'    => $sc->first_visit_date ? $sc->first_visit_date->toDateString() : $visit1Date,
                    'status'        => 'Completed',
                    'amount'        => $sc->amount ?: 0,
                    'notes'         => 'Visit 1 of Monthly 2-Visit Package',
                ]);
            }

            // Visit 2 Sync
            $exists2 = CustomerVisit::where('customer_id', $sc->client_id)
                ->whereYear('visit_date', $year)
                ->whereMonth('visit_date', $month)
                ->where('visit_number', 2)
                ->exists();

            if (!$exists2) {
                CustomerVisit::create([
                    'customer_id'   => $sc->client_id,
                    'customer_name' => $sc->name,
                    'contact'       => $sc->contact,
                    'service_type'  => 'Solar Cleaning',
                    'visit_number'  => 2,
                    'visit_date'    => $sc->second_visit_date ? $sc->second_visit_date->toDateString() : $visit2Date,
                    'status'        => 'Scheduled',
                    'amount'        => $sc->second_amount ?: ($sc->amount ?: 0),
                    'notes'         => 'Visit 2 of Monthly 2-Visit Package',
                ]);
            }
        }
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
            ->orWhere('campaign_name', 'LIKE', "%{$query}%")
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
            ->update(['status' => 'completed', 'completed_at' => now('Asia/Karachi')]);

        return back()->with('success', 'Reminder dismissed for ' . $solarClient->name);
    }
}
