<!DOCTYPE html>
<html>

<head>
    <title>Cetak Master Item</title>
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
    <h3>Daftar Item</h3>
    @if (request('q') || request('active') != '')
        <p>Filter:
            {{ request('q') ? '“' . request('q') . '”' : '' }}
            {{ request('active') == '1' ? 'Aktif' : '' }}
            {{ request('active') == '0' ? 'Nonaktif' : '' }}
        </p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Unit</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i)
                <tr>
                    <td>{{ $i->code }}</td>
                    <td>{{ $i->name }}</td>
                    <td>{{ $i->unit }}</td>
                    <td style="text-align:right">{{ number_format($i->price, 2) }}</td>
                    <td>{{ $i->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
