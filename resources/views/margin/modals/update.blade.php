@foreach ($margins as $item)
    <div class="modal modal-right large fade" id="updateData{{ $item->idmargin_penjualan }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Margin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('margin-penjualan/' . $item->idmargin_penjualan . '/update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        
                        <div class="form-group mb-3">
                            <label for="persen" class="form-label">Persen</label>
                            <input type="text" class="form-control" id="persen" name="persen"
                                value="{{ $item->persen }}" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
