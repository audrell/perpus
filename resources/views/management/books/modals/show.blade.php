@foreach($books as $book)
    <div class="modal fade" id="modalShowBook{{ $book->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">detail buku</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/' . $book->cover_path) }}" class="img-fluid mb-3 rounded" style="max-height: 200px;">
                    <ul class="list-group list-group-flush text-left">
                        <li class="list-group-item"><strong>judul:</strong> {{ $book->title }}</li>
                        <li class="list-group-item"><strong>isbn:</strong> {{ $book->isbn }}</li>
                        <li class="list-group-item"><strong>stok:</strong> {{ $book->quantity_available }} / {{ $book->quantity_total }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditBook{{ $book->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark">edit buku</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>judul buku</label>
                                <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>kategori</label>
                                <select name="category_id" class="form-control">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $book->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label>ganti cover (kosongkan jika tidak diubah)</label>
                            <input type="file" name="cover" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
