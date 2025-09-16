{{-- resources/views/delivery-orders/print.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cetak Delivery Order {{ $dos->first()->do_number ?? '' }}</title>
    <style>
        /* Global resets */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            color: #333;
            padding: 20px;
        }

        h1,
        h2,
        h3 {
            margin-bottom: 8px;
        }

        p {
            margin-bottom: 4px;
        }

        /* Header */
        .header {
            border-bottom: 2px solid #4A5568;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 1.5rem;
            color: #2D3748;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 8px;
        }

        .meta-item {
            min-width: 200px;
        }

        .meta-item label {
            font-weight: bold;
            display: block;
            color: #4A5568;
            margin-bottom: 2px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        th,
        td {
            padding: 8px 12px;
            border: 1px solid #CBD5E0;
            text-align: left;
            font-size: 0.9rem;
        }

        thead th {
            background-color: #EDF2F7;
            color: #2D3748;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #F7FAFC;
        }

        /* Footer */
        .footer {
            font-size: 0.8rem;
            color: #718096;
            text-align: right;
            margin-top: 20px;
        }

        /* Print-specific */
        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="header">
        <h1>Delivery Order</h1>
        <div class="meta">
            <div class="meta-item">
                <label>DO Number</label>
                <p>{{ $dos->first()->do_number ?? '-' }}</p>
            </div>
            <div class="meta-item">
                <label>SO Number</label>
                <p>{{ $dos->first()->salesOrder->so_number ?? '-' }}</p>
            </div>
            <div class="meta-item">
                <label>Customer</label>
                <p>{{ $dos->first()->salesOrder->customer->name ?? '-' }}</p>
            </div>
            <div class="meta-item">
                <label>Delivery Date</label>
                <p>{{ optional($dos->first()->delivery_date)->format('d-m-Y') }}</p>
            </div>
            <div class="meta-item">
                <label>Status</label>
                <p>{{ ucfirst($dos->first()->status ?? '') }}</p>
            </div>
        </div>
    </div>

    <h3>Line Items</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th style="width: 80px;">Qty</th>
                <th style="width: 80px;">Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dos->first()->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->salesOrderItem->item->code }}</td>
                    <td>{{ $item->salesOrderItem->item->name }}</td>
                    <td>{{ $item->shipped_qty }}</td>
                    <td>{{ $item->salesOrderItem->item->unit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer no-print">
        Printed: {{ now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>
