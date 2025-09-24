{{-- resources/views/sales/export-pdf.blade.php --}}
@php
    $companyName = $companyName ?? config('app.name', 'Perusahaan Asuransi');
    $companyAddress = $companyAddress ?? '';
    $logoPath = $logoPath ?? public_path('logo.png'); // ganti jika perlu
    $hasLogo = file_exists($logoPath);
    $count = $rows->count();
    $sumPremium = $rows->sum('premium');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        /* --- Page setup --- */
        @page {
            margin: 90px 40px 70px 40px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            position: fixed;
            top: -65px;
            left: 0;
            right: 0;
            height: 65px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 50px;
            color: #666;
        }

        .pagenum:before {
            content: counter(page) " / " counter(pages);
        }

        .muted {
            color: #666;
        }

        .small {
            font-size: 11px;
        }

        .xsmall {
            font-size: 10px;
        }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
        }

        .tag-blue {
            background: #E6F0FF;
            color: #1D4ED8;
        }

        .wrap {
            page-break-inside: avoid;
        }

        /* --- Layout --- */
        .flex {
            display: flex;
            align-items: center;
        }

        .between {
            justify-content: space-between;
        }

        .mt-0 {
            margin-top: 0
        }

        .mt-6 {
            margin-top: 6px
        }

        .mt-8 {
            margin-top: 8px
        }

        .mt-12 {
            margin-top: 12px
        }

        .mt-16 {
            margin-top: 16px
        }

        .mb-0 {
            margin-bottom: 0
        }

        .mb-6 {
            margin-bottom: 6px
        }

        .mb-8 {
            margin-bottom: 8px
        }

        .mb-12 {
            margin-bottom: 12px
        }

        .mb-16 {
            margin-bottom: 16px
        }

        .w-100 {
            width: 100%
        }

        /* --- Table --- */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background: #f7f8fa;
            text-align: left;
            font-weight: 600;
            color: #374151;
        }

        tr:nth-child(even) td {
            background: #fcfdff;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* --- Summary cards --- */
        .cards {
            display: flex;
            gap: 10px;
        }

        .card {
            flex: 1;
            border: 1px solid #eef1f5;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .card h4 {
            margin: 0 0 4px;
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }

        .card p {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        /* --- Divider --- */
        .divider {
            height: 1px;
            background: #eef1f5;
            margin: 14px 0;
        }

        /* --- Signature --- */
        .sign {
            margin-top: 18px;
            display: flex;
            gap: 24px;
        }

        .sign .box {
            width: 220px;
        }

        .sign .line {
            height: 40px;
            border-bottom: 1px dashed #cbd5e1;
            margin-top: 30px;
        }

        /* --- Logo --- */
        .logo {
            height: 40px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="flex between">
            <div class="flex" style="gap:10px;">
                @if ($hasLogo)
                    <img class="logo" src="{{ $logoPath }}" alt="logo">
                @endif
                <div>
                    <div style="font-weight:800; font-size:14px;">{{ $companyName }}</div>
                    @if ($companyAddress)
                        <div class="xsmall muted">{{ $companyAddress }}</div>
                    @endif
                </div>
            </div>
            <div class="small muted" style="text-align:right;">
                Dibuat: {{ $generatedAt ?? now()->format('d/m/Y H:i') }}<br>
                @if (!empty($filters['month']))
                    Bulan: {{ $filters['month'] }}<br>
                @endif
                @if (!empty($filters['q']))
                    Cari: “{{ $filters['q'] }}”<br>
                @endif
            </div>
        </div>
        <div class="divider"></div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="divider"></div>
        <div class="flex between">
            <div class="xsmall muted">Laporan Penjualan • {{ $companyName }}</div>
            <div class="xsmall muted">Halaman <span class="pagenum"></span></div>
        </div>
    </div>

    {{-- TITLE --}}
    <h2 class="mt-16 mb-8" style="font-size:18px; font-weight:800;">Laporan Penjualan</h2>

    {{-- SUMMARY CARDS --}}
    <div class="cards mb-12">
        <div class="card">
            <h4>Total Data</h4>
            <p>{{ number_format($count, 0, ',', '.') }} transaksi</p>
        </div>
        <div class="card">
            <h4>Total Premi</h4>
            <p>Rp {{ number_format($sumPremium, 2, ',', '.') }}</p>
        </div>
        @if (!empty($filters['month']))
            <div class="card">
                <h4>Periode</h4>
                <p>{{ $filters['month'] }}</p>
            </div>
        @endif
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width: 90px;">Tanggal</th>
                <th>Nasabah</th>
                <th style="width: 28%;">Produk</th>
                <th style="width: 80px;">Case</th>
                <th class="text-right" style="width: 120px;">Premi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $r)
                <tr>
                    <td>{{ optional($r->sale_date)->format('Y-m-d') }}</td>
                    <td>{{ $r->customer_name }}</td>
                    <td>{{ $r->product->name ?? '-' }}</td>
                    <td>
                        <span class="tag tag-blue">Case {{ $r->case_level }}</span>
                    </td>
                    <td class="text-right">Rp {{ number_format($r->premium, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center muted">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- SIGNATURE --}}
    <div class="sign">
        <div class="box">
            <div class="small muted">Disusun oleh,</div>
            <div class="line"></div>
            <div class="xsmall muted">Nama & Tanda Tangan</div>
        </div>
        <div class="box">
            <div class="small muted">Disetujui,</div>
            <div class="line"></div>
            <div class="xsmall muted">Nama & Tanda Tangan</div>
        </div>
    </div>

</body>

</html>
