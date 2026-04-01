@foreach($books as $book)

<div class="modal fade" id="modalEditBook{{ $book->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white">edit buku: {{ $book->title }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>isbn</label>
                                <input type="text" name="isbn" class="form-control" value="{{ $book->isbn }}" required>
                            </div>
                            <div class="form-group">
                                <label>judul buku</label>
                                <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
                            </div>
                            <div class="form-group">
                                <label>penulis</label>
                                <input type="text" name="author" class="form-control" value="{{ $book->author }}" required>
                            </div>
                            <div class="form-group">
                                <label>penerbit</label>
                                <input type="text" name="publisher" class="form-control" value="{{ $book->publisher }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>tahun terbit</label>
                                <input type="number" name="year" class="form-control" value="{{ $book->year }}" required>
                            </div>
                            <div class="form-group">
                                <label>kategori</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>lokasi rak</label>
                                <input type="text" name="rack_location" class="form-control" value="{{ $book->rack_location }}">
                            </div>
                            <div class="form-group">
                                <label>stok total</label>
                                <input type="number" name="quantity_total" class="form-control" value="{{ $book->quantity_total }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>ganti cover (kosongkan jika tidak ingin mengubah)</label>
                        <input type="file" name="cover" class="form-control-file">
                        <small class="text-muted">cover saat ini: {{ $book->cover_path }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">batal</button>
                    <button type="submit" class="btn btn-warning">update data</button>
                </div>
            </form>
        </div>
    </div>
</div>

                                @endforeach
