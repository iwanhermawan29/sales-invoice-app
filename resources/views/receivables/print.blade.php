{{-- resources/views/receivables/print.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daftar Receivables</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333
        }

        h1 {
            text-align: center;
            margin-bottom: 16px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 13px
        }

        thead th {
            background: #f0f0f0;
            text-align: left
        }

        tbody tr:nth-child(even) {
            background: #fafafa
        }

        .text-right {
            text-align: right
        }

        @media print {
            body {
                padding: 0
            }
        }
    </style>
</head>

<body onload="window.print()">
    <h1>Daftar Receivables</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice#</th>
                <th>Customer</th>
                <th>Due Date</th>
                <th class="text-right">Amt Due</th>
                <th class="text-right">Amt Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receivables as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r->invoice->invoice_number }}</td>
                    <td>
                        {{ optional($r->invoice->deliveryOrder->salesOrder->customer)->name }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($r->due_date)->format('d-m-Y') }}</td>
                    <td class="text-right">{{ number_format($r->amount_due, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($r->amount_paid, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($r->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
