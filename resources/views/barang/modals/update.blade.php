@foreach ($barang as $b)
    <div class="modal modal-right large fade" id="updateData{{ $b->idbarang }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('barang/'.$b->idbarang.'/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        {{-- Jenis Barang --}}
                        <div class="form-group mb-3">
                            <label for="jenis" class="form-label">Jenis Barang</label>
                            <select class="form-select" id="jenis{{ $b->idbarang }}" name="jenis">
                                <option value="" disabled>-- Jenis Barang --</option>
                                <option value="M" @selected(old('jenis', $b->jenis_barang) == 'Makanan/Minuman')>
                                    Makanan/Minuman
                                </option>
                                <option value="S" @selected(old('jenis', $b->jenis_barang) == 'Sabun/Perawatan')>
                                    Sabun/Perawatan
                                </option>


                            </select>
                        </div>

                        {{-- Nama Barang --}}
                        <div class="form-group mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ $b->nama_barang}}" required>
                        </div>

                        {{-- Dropdown Satuan --}}
                        <div class="form-group mb-3">
                            <label for="idsatuan{{ $b->idbarang }}" class="form-label">Satuan</label>
                            <select class="form-select" id="idsatuan" name="idsatuan" required>
                                <option value="" disabled>-- Pilih Satuan --</option>
                                @foreach ($satuan as $item)
                                    <option value="{{ $item->idsatuan }}" @selected(old('idsatuan', $b->nama_satuan) == $item->nama_satuan)>
                                        {{ $item->nama_satuan }}{{ $item->status ? '' : ' (tidak aktif)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga Barang --}}
                        <div class="form-group mb-3">
                            <label for="harga" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" id="harga" name="harga" min="0"
                                value="{{ $b->harga }}" required>
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
