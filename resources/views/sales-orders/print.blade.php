<!DOCTYPE html>
<html>

<head>
    <title>Cetak Sales Orders</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body onload="window.print()">
    <h3>Daftar Sales Orders</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>SO Number</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salesOrders as $so)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $so->so_number }}</td>
                    <td>{{ $so->customer->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($so->order_date)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($so->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
