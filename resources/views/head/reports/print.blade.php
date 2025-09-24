<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        * {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px
        }

        th {
            background: #f3f4f6;
            text-align: left
        }

        .text-right {
            text-align: right
        }

        .meta {
            margin-bottom: 10px;
            color: #555
        }
    </style>
</head>

<body>
    <h3>Laporan Penjualan</h3>
    <div class="meta">
        Dibuat: {{ $generatedAt }}
        @foreach ($filters as $k => $v)
            • {{ ucfirst($k) }}: {{ $v }}
        @endforeach
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th>Produk</th>
                <th>Case</th>
                <th>Premi</th>
                <th>Agent</th>
                <th>Kota</th>
                <th>Agency</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $r)
                <tr>
                    <td>{{ optional($r->sale_date)->format('Y-m-d') }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td>{{ $r->product_name }}</td>
                    <td>Case {{ $r->case_level }}</td>
                    <td class="text-right">{{ number_format($r->premium, 2, ',', '.') }}</td>
                    <td>{{ $r->agent_name }}</td>
                    <td>{{ $r->kota }}</td>
                    <td>{{ $r->agency_name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
