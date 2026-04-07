<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Form Peminjaman Buku</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('loans.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Pilih Member</label>
                                <select name="member_id" class="form-select" required>
                                    <option value="">-- Pilih Nama Peminjam --</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->member_code }} - {{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Buku (Tahan Ctrl untuk pilih banyak)</label>
                                <select name="book_ids[]" class="form-select" size="5" multiple required>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}">
                                            {{ $book->title }} (Tersedia: {{ $book->quantity_available }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan Peminjaman</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
