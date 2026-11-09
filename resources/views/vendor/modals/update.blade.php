@foreach ($vendors as $item)
    <div class="modal modal-right large fade" id="updateData{{ $item->idvendor }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('vendor/' . $item->idvendor . '/update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        
                        <div class="form-group mb-3">
                            <label for="nama_vendor" class="form-label">Nama Vendor</label>
                            <input type="text" class="form-control" id="nama_vendor" name="nama_vendor"
                                value="{{ $item->nama_vendor }}" required>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="badan_hukum" class="form-label">Badan Hukum</label>
                            <select class="form-select" id="badan_hukum" name="badan_hukum" required>
                                <option value="Y" @selected($item->badan_hukum == 'Y')>Berbadan Hukum</option>
                                <option value="N" @selected($item->badan_hukum == 'N')>Non Badan Hukum</option>
                            </select>
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
