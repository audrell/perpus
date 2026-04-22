<!-- Modal Upload Import -->
<div class="modal fade" id="modalImportBook" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    📥 Import Buku (Excel)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('books.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <!-- Info -->
                    <div class="alert alert-light border mb-3 small">
                        <strong>Catatan:</strong><br>
                        1. Download template terlebih dahulu<br>
                        2. Isi data buku di file Excel<br>
                        3. Upload kembali file tersebut
                    </div>

                    <!-- File Input -->
                    <div class="form-group mb-2">
                        <label class="font-weight-bold mb-1">Pilih File Excel</label>
                        <input
                            type="file"
                            name="import_file"
                            class="form-control-file"
                            accept=".xlsx,.xls"
                            required
                        >
                        <small class="text-muted">
                            Format: .xlsx atau .xls | Max: 5MB
                        </small>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
