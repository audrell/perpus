<div class="row">
    <div class="col-md-6">
        <table class="table table-borderless table-sm">
            <tr><th>Kode Pinjam</th><td>: {{ $loan->loan_code }}</td></tr>
            <tr><th>Peminjam</th><td>: {{ $loan->member->name }}</td></tr>
            <tr><th>Status</th><td>: <span class="badge badge-info">{{ $loan->approval_status }}</span></td></tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-borderless table-sm">
            <tr><th>Tgl Pinjam</th><td>: {{ $loan->loaned_at->format('d-m-Y') }}</td></tr>
            <tr><th>Tgl Kembali</th><td>: {{ $loan->due_date->format('d-m-Y') }}</td></tr>
        </table>
    </div>
</div>
<hr>
<h6>Daftar Buku:</h6>
<table class="table table-striped table-sm">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>ISBN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($loan->loanItems as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->book->title }}</td>
            <td>{{ $item->book->isbn }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
