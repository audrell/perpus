<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 10px; color: #555; margin-bottom: 16px; }
        .info { margin-bottom: 10px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #5b6abf; color: white; padding: 7px; text-align: center; }
        td { padding: 6px 8px; text-align: center; border: 1px solid #ccc; }
        tr:nth-child(even) { background-color: #f5f5f5; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 10px; color: white; display: inline-block; }
        .returned { background-color: #28a745; }
        .borrowed { background-color: #007bff; }
        .approved { background-color: #17a2b8; }
        .pending  { background-color: #ffc107; color: #333; }
        .rejected { background-color: #dc3545; }
        .footer { margin-top: 20px; font-size: 10px; text-align: right; color: #777; }
    </style>
</head>
<body>

    <h2>{{ $setting->app_name ?? 'Perpustakaan' }}</h2>
    <div class="subtitle">Laporan Data Peminjaman Buku</div>

    <div class="info">
        @if($filterStatus)
            <strong>Status:</strong> {{ ucfirst(strtolower($filterStatus)) }} &nbsp;|&nbsp;
        @endif
        @if($filterApprovalStatus)
            <strong>Approval:</strong> {{ ucfirst(strtolower($filterApprovalStatus)) }} &nbsp;|&nbsp;
        @endif
        @if($startDate || $endDate)
            <strong>Periode:</strong>
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }}
            s/d
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pinjam</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Approval</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan->loan_code }}</td>
                <td>{{ $loan->member->name ?? '-' }}</td>
                <td>{{ $loan->loaned_at?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $loan->due_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $loan->returned_at?->format('d/m/Y') ?? '-' }}</td>
                <td>
                    <span class="badge {{ strtolower($loan->status) === 'returned' ? 'returned' : 'borrowed' }}">
                        {{ strtoupper($loan->status) }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ strtolower($loan->approval_status) }}">
                        {{ strtoupper($loan->approval_status) }}
                    </span>
                </td>
                <td>Rp {{ number_format($loan->fine_total ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;">Tidak ada data peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
