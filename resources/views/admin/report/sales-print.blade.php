<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        .container { max-width: 1100px; margin: 0 auto; padding: 20px; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th,td { padding:8px 10px; border:1px solid #ddd; font-size:13px }
        th { background:#f7f7f7; text-align:left }
        .totals { margin-top:12px; font-weight:bold }
        @media print {
            .no-print { display:none }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Sales Report</h1>
                <p>Generated: {{ now()->format('d M Y H:i') }}</p>
            </div>
            <div class="no-print">
                <button onclick="window.print()">Print</button>
            </div>
        </div>

        <div>
            <p>Total Orders: {{ $totalOrders }}</p>
            <p>Total Revenue: Rp {{ number_format($totalRevenue ?? 0,0,',','.') }}</p>
        </div>

        {{-- Top selling products --}}
        <div style="margin-top:20px; margin-bottom:20px;">
            <h3>Top Selling Products</h3>
            @if(!empty($topProducts) && $topProducts->count())
                <table style="width:100%; border-collapse:collapse; margin-top:10px;">
                    <thead>
                        <tr style="background:#f7f7f7; border:1px solid #ddd;">
                            <th style="padding:8px 10px; border:1px solid #ddd; text-align:left; font-weight:bold; font-size:13px;">Product</th>
                            <th style="padding:8px 10px; border:1px solid #ddd; text-align:left; font-weight:bold; font-size:13px;">Quantity Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $p)
                            <tr style="border:1px solid #ddd;">
                                <td style="padding:8px 10px; border:1px solid #ddd; font-size:13px;">{{ $p->name }}</td>
                                <td style="padding:8px 10px; border:1px solid #ddd; font-size:13px;">{{ $p->total_sold }} pcs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No product sales in this range.</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $i => $order)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer_name ?? ($order->user?->full_name ?? 'Guest') }} ({{ $order->customer_email }})</td>
                        <td>Rp {{ number_format($order->total,0,',','.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
