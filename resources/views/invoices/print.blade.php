@php
    $invoice = $invoices->first();
@endphp

{{-- resources/views/invoices/print.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>INVOICE {{ $invoice->invoice_number }}</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .wrapper {
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .company-info {
            font-size: 14px;
            line-height: 1.5;
        }

        .company-info strong {
            display: block;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .logo img {
            max-height: 60px;
        }

        /* BillTo / Details */
        .top-block {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .bill-to,
        .inv-details {
            width: 48%;
            font-size: 14px;
            line-height: 1.4;
        }

        .bill-to strong,
        .inv-details strong {
            display: inline-block;
            width: 80px;
        }

        .inv-details {
            text-align: right;
        }

        /* Items table */
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items th,
        .items td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .items th {
            background: #007bff;
            color: #fff;
            font-weight: normal;
            text-align: left;
        }

        .items tbody tr:nth-child(even) {
            background: #f7f7f7;
        }

        .text-right {
            text-align: right;
        }

        /* Payment + Summary */
        .bottom-block {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .instructions {
            width: 48%;
            font-size: 13px;
            line-height: 1.4;
        }

        .summary {
            width: 48%;
            font-size: 14px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            padding: 6px 8px;
        }

        .summary .label {
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            font-size: 14px;
            line-height: 2;
        }

        @media print {
            body {
                padding: 0;
            }

            .wrapper {
                border: none;
                border-radius: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="wrapper">
        {{-- Title --}}
        <h1>INVOICE</h1>

        {{-- Company header --}}
        <div class="header">
            <div class="company-info">
                <strong>Sahlan Aqiqah</strong>
                {{ config('app.address', 'Jl Raya Serang km 12,5') }}<br>
                Mobile: {{ config('app.phone', '1234568') }}<br>
                Email: {{ config('app.email', 'sahlah.aqiqah@gmail.com') }}
            </div>
            <div class="logo">
                <img src="{{ asset('logo.png') }}" alt="Logo">
            </div>
        </div>

        {{-- Bill To & Invoice details --}}
        <div class="top-block">
            <div class="bill-to">
                <strong>Bill To:</strong><br>
                {{ $invoice->deliveryOrder->salesOrder->customer->name }}<br>
                {{ $invoice->deliveryOrder->salesOrder->customer->address }}<br>
                Tel: {{ $invoice->deliveryOrder->salesOrder->customer->phone }}<br>
                Email: {{ $invoice->deliveryOrder->salesOrder->customer->email }}
            </div>
            <div class="inv-details">
                <p><strong>Invoice No :</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Invoice Date :</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                <p><strong>Due Date :</strong> {{ optional($invoice->due_date)->format('M d, Y') }}</p>
            </div>
        </div>

        {{-- Line items --}}
        <table class="items">
            <thead>
                <tr>
                    <th style="width:40px">Sl.</th>
                    <th>Description</th>
                    <th style="width:60px">Qty</th>
                    <th style="width:100px">Rate</th>
                    <th style="width:100px" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->salesOrderItem->item->name }}</td>
                        <td>{{ $item->salesOrderItem->quantity }}</td>
                        <td>{{ number_format($item->amount / $item->salesOrderItem->quantity, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->amount, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Payment instructions & totals --}}
        <div class="bottom-block">
            <div class="instructions">
                <strong>Payment Instructions</strong><br>
                Please remit payment to:<br>
                Bank XYZ, Account No: 12345678<br>
                SWIFT: XYZABCD<br>
                Thank you for your business!
            </div>
            <div class="summary">
                <table>
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="text-right">{{ number_format($invoice->items->sum('amount'), 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total</td>
                        <td class="text-right">{{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                    </tr>
                    {{-- optionally show paid & balance --}}
                    {{-- <tr>
            <td class="label">Paid</td>
            <td class="text-right">{{ number_format($invoice->paid_amount,2,',','.') }}</td>
          </tr>
          <tr>
            <td class="label">Balance Due</td>
            <td class="text-right">{{ number_format($invoice->total_amount - $invoice->paid_amount,2,',','.') }}</td>
          </tr> --}}
                </table>
            </div>
        </div>

        {{-- Signature --}}
        <div class="signature">
            ___________________________<br>
            Authorized Signatory
        </div>
    </div>
</body>

</html>
