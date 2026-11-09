@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Retur Barang'])

        <p class="text-muted">Retur dibuat dari halaman detail penerimaan (tombol <em>Buat Retur</em>).</p>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>No.</th><th>Tanggal</th><th>Penerimaan</th><th>Vendor</th><th>Petugas</th><th>Total Barang</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($retur as $t)
                        <tr class="text-center">
                            <th scope="row">#{{ $t->idretur }}</th>
                            <td>{{ $t->tanggal }}</td>
                            <td><a href="{{ route('penerimaan.show', $t->idpenerimaan) }}">#{{ $t->idpenerimaan }}</a></td>
                            <td>{{ $t->nama_vendor }}</td>
                            <td>{{ $t->nama_user }}</td>
                            <td>{{ $t->total_barang }}</td>
                            <td><a href="{{ route('retur.show', $t->idretur) }}" class="btn btn-sm btn-outline-info">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada retur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $retur->links() }}
    </div>
@endsection
