<div class="modal fade" id="modalCreateBook" tabindex="-1" role="dialog" aria-labelledby="modalCreateBookLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <div class="modal-content">
            <div class="modal-header bg-primary text-white">
    <h5 class="modal-title" id="modalCreateBookLabel">Add New Book</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Buku</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>ISBN</label>
                                <input type="text" name="isbn" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Penulis</label>
                                <input type="text" name="author" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Penerbit</label>
                                <input type="text" name="publisher" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Rak</label>
                                <input type="text" name="rack_location" class="form-control" placeholder="Contoh: A-1">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" name="year" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Total Stok</label>
                                        <input type="number" name="quantity_total" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Cover Buku</label>
                                <input type="file" name="cover" class="form-control-file">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
 </div>
