<div class="modal modal-right large fade" id="createData" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    {{-- Jenis Barang --}}
                    <div class="form-group mb-3">
                        <label for="jenis_barang" class="form-label">Jenis Barang</label>
                        <select class="form-select" id="jenis" name="jenis">
                            <option value="" disabled selected>-- Jenis Barang --</option>
                            <option value="M" @selected(old('jenis')=='M' )>
                                Makanan/Minuman
                            </option>
                            <option value="S" @selected(old('jenis')=='S' )>
                                Sabun/Perawatan
                            </option>


                        </select>
                    </div>

                    {{-- Nama Barang --}}
                    <div class="form-group mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama_barang') }}" required>
                    </div>

                    {{-- Dropdown Satuan --}}
                    <div class="form-group mb-3">
                        <label for="idsatuan" class="form-label">Satuan</label>
                        <select class="form-select" id="idsatuan" name="idsatuan" required>
                            <option value="" disabled selected>-- Pilih Satuan --</option>
                            @foreach($satuan as $item)
                            <option value="{{ $item->idsatuan }}" @selected(old('idsatuan')==$item->idsatuan)>
                                {{ $item->nama_satuan }}{{ $item->status ? '' : ' (tidak aktif)' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga Barang --}}
                    <div class="form-group mb-3">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="harga" name="harga" min="0" value="{{ old('harga') }}" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    {{-- Mengubah type="button" menjadi type="submit" --}}
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>