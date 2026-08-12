<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marketing Leads Report — Asan Home Service</title>
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
            border-bottom: 2px solid #3182ce;
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
            padding: 10px;
            text-align: center;
        }
        .summary-card-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #718096;
        }
        .summary-card-value {
            font-size: 20px;
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
        .text-info { color: #3182ce; }
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
                <div class="report-title">Marketing Leads Summary Report</div>
            </td>
            <td class="meta-text">
                <div><strong>Date Range:</strong> {{ $fromDate }} to {{ $toDate }}</div>
                <div><strong>Generated:</strong> {{ $generatedAt }}</div>
            </td>
        </tr>
    </table>

    {{-- Summary Box Cards --}}
    <table class="summary-cards">
        <tr>
            <td class="summary-card" style="border-top:3px solid #3182ce;">
                <div class="summary-card-title">Total Leads</div>
                <div class="summary-card-value">{{ number_format($metrics['total']) }}</div>
            </td>
            <td class="summary-card" style="border-top:3px solid #dd6b20;">
                <div class="summary-card-title">Pending</div>
                <div class="summary-card-value text-warning">{{ number_format($metrics['pending']) }}</div>
            </td>
            <td class="summary-card" style="border-top:3px solid #3182ce;">
                <div class="summary-card-title">Follow Up</div>
                <div class="summary-card-value text-info">{{ number_format($metrics['follow_up']) }}</div>
            </td>
            <td class="summary-card" style="border-top:3px solid #2f855a;">
                <div class="summary-card-title">Done</div>
                <div class="summary-card-value text-success">{{ number_format($metrics['done']) }}</div>
            </td>
            <td class="summary-card" style="border-top:3px solid #c53030;">
                <div class="summary-card-title">Cancel</div>
                <div class="summary-card-value text-danger">{{ number_format($metrics['cancel']) }}</div>
            </td>
        </tr>
    </table>

    {{-- Service Breakdown --}}
    <div class="section-title">1. Service Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Service Type</th>
                <th class="text-center">Total Leads</th>
                <th class="text-center">Pending</th>
                <th class="text-center">Follow Up</th>
                <th class="text-center">Done</th>
                <th class="text-center">Cancel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($serviceBreakdown as $stName => $stStats)
            <tr>
                <td class="font-bold">{{ $stName }}</td>
                <td class="text-center font-bold">{{ $stStats['total'] }}</td>
                <td class="text-center text-warning font-bold">{{ $stStats['pending'] }}</td>
                <td class="text-center text-info font-bold">{{ $stStats['follow_up'] }}</td>
                <td class="text-center text-success font-bold">{{ $stStats['done'] }}</td>
                <td class="text-center text-danger font-bold">{{ $stStats['cancel'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Campaign Performance --}}
    <div class="section-title">2. Campaign Performance</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Campaign Name</th>
                <th class="text-center">Total Leads</th>
                <th class="text-center">Done Leads</th>
                <th class="text-center">Cancel Leads</th>
                <th class="text-center">Conversion Rate</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($campaignBreakdown))
                <tr>
                    <td colspan="5" class="text-center text-muted">No campaign data available for this report.</td>
                </tr>
            @else
                @foreach($campaignBreakdown as $cName => $cStats)
                @php
                    $rate = $cStats['total'] > 0 ? round(($cStats['done'] / $cStats['total']) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="font-bold">{{ $cName }}</td>
                    <td class="text-center font-bold">{{ $cStats['total'] }}</td>
                    <td class="text-center text-success font-bold">{{ $cStats['done'] }}</td>
                    <td class="text-center text-danger font-bold">{{ $cStats['cancel'] }}</td>
                    <td class="text-center font-bold text-success">{{ $rate }}%</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="footer">
        Asan Home Service Management System — Confidential Business Report — Page 1
    </div>

</body>
</html>
