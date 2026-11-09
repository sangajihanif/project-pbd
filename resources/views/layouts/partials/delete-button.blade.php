{{-- Tombol hapus dengan konfirmasi. Pakai: @include('layouts.partials.delete-button', ['action' => route(...), 'teks' => 'Hapus data ini?']) --}}
<form action="{{ $action }}" method="POST" class="d-inline m-0"
    onsubmit="return confirm({!! json_encode($teks ?? 'Yakin ingin menghapus data ini?', JSON_HEX_APOS | JSON_HEX_QUOT) !!})">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" style="width: 70px;">Hapus</button>
</form>
