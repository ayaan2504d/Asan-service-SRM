<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Dashboard Report — Asan Home Service</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2d3748;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2b6cb0;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2b6cb0;
            margin: 0;
        }
        .report-title {
            font-size: 16px;
            color: #4a5568;
            margin: 4px 0 0 0;
        }
        .meta-text {
            text-align: right;
            font-size: 11px;
            color: #718096;
        }
        .summary-cards {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: separate;
            border-spacing: 8px;
        }
        .summary-card {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }
        .summary-card-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #718096;
        }
        .summary-card-value {
            font-size: 18px;
            font-weight: bold;
            color: #1a202c;
            margin-top: 4px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2b6cb0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 20px;
            margin-bottom: 12px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .data-table th {
            background-color: #ebf8ff;
            color: #2c5282;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            border-bottom: 1px solid #bee3f8;
        }
        .data-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-success { color: #2f855a; }
        .text-warning { color: #dd6b20; }
        .text-danger { color: #c53030; }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #a0aec0;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="company-name">{{ $companyName }}</h1>
                <div class="report-title">Business Analytics & Financial Report</div>
            </td>
            <td class="meta-text">
                <div><strong>Generated At:</strong> {{ $generatedAt }}</div>
                <div><strong>System:</strong> Management & SRM Dashboard</div>
            </td>
        </tr>
    </table>

    {{-- Financial Summary Cards --}}
    <table class="summary-cards">
        <tr>
            <td class="summary-card" style="border-top:3px solid #2f855a;">
                <div class="summary-card-title">Total Earnings</div>
                <div class="summary-card-value text-success">₨ {{ number_format($totalEarnings, 0) }}</div>
            </td>
            <td class="summary-card" style="border-top:3px solid #c53030;">
                <div class="summary-card-title">Total Expenses</div>
                <div class="summary-card-value text-danger">₨ {{ number_format($totalExpenses, 0) }}</div>
            </td>
            <td class="summary-card" style="border-top:3px solid #3182ce;">
                <div class="summary-card-title">Total Profit</div>
                <div class="summary-card-value text-info">₨ {{ number_format($totalProfit, 0) }}</div>
            </td>
        </tr>
    </table>

    {{-- Service Wise Earnings --}}
    <div class="section-title">1. Service Wise Earnings</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Service Name</th>
                <th class="text-right">Total Income</th>
                <th class="text-right">% Share</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Solar Cleaning</td>
                <td class="text-right font-bold text-success">₨ {{ number_format($solarEarnings, 0) }}</td>
                <td class="text-right">{{ $totalEarnings > 0 ? round(($solarEarnings / $totalEarnings) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td class="font-bold">Sofa Cleaning</td>
                <td class="text-right font-bold text-success">₨ {{ number_format($sofaEarnings, 0) }}</td>
                <td class="text-right">{{ $totalEarnings > 0 ? round(($sofaEarnings / $totalEarnings) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td class="font-bold">Deep Cleaning</td>
                <td class="text-right font-bold text-success">₨ {{ number_format($deepEarnings, 0) }}</td>
                <td class="text-right">{{ $totalEarnings > 0 ? round(($deepEarnings / $totalEarnings) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>

    {{-- Reminders & Follow-Ups Due --}}
    <div class="section-title">2. Due Service Reminders (14 Days)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Service</th>
                <th>Reminder Due Date</th>
            </tr>
        </thead>
        <tbody>
            @if($due14DayReminders->isEmpty())
                <tr>
                    <td colspan="5" class="text-center text-muted">No pending service reminders due at this time.</td>
                </tr>
            @else
                @foreach($due14DayReminders as $rem)
                <tr>
                    <td class="font-bold">{{ $rem->customer_id ?: 'SR-'.$rem->id }}</td>
                    <td class="font-bold">{{ $rem->customer_name }}</td>
                    <td>{{ $rem->contact }}</td>
                    <td>{{ $rem->service_type }}</td>
                    <td class="text-danger font-bold">{{ $rem->next_reminder_date?->format('d M Y') }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="footer">
        Asan Home Service Management System — Business Executive Report — Page 1
    </div>

</body>
</html>
