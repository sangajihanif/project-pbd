@foreach ($user as $item)
    <div class="modal modal-right large fade" id="updateData{{ $item->iduser }}" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('user/' . $item->iduser . '/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nama User</label>
                            <input type="text" class="form-control" id="nama_user{{ $item->iduser }}" name="name"
                                value="{{ $item->nama_user}}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_role" class="form-label">Role</label>
                            <select class="form-select" id="nama_role{{ $item->id }}" name="idrole" required>
                                <option value="1" @selected($item->nama_role == 'Super Admin')>Super Admin</option>
                                <option value="2" @selected($item->nama_role == 'Admin Gudang')>Admin Gudang</option>
                                <option value="3" @selected($item->nama_role == 'Kasir')>Kasir</option>
                                <option value="4" @selected($item->nama_role == 'Tamu')>Tamu</option>
                                <option value="5" @selected($item->nama_role == 'Admin Sistem')>Admin Sistem</option>
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
