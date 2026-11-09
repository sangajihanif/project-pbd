@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Penerimaan Barang'])

        <p class="text-muted">Penerimaan dibuat dari halaman detail pengadaan (tombol <em>Terima Barang</em>).</p>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>No.</th><th>Tanggal</th><th>Pengadaan</th><th>Vendor</th><th>Petugas</th><th>Item</th><th>Nilai</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penerimaan as $r)
                        <tr class="text-center">
                            <th scope="row">#{{ $r->idpenerimaan }}</th>
                            <td>{{ $r->tanggal }}</td>
                            <td><a href="{{ route('pengadaan.show', $r->idpengadaan) }}">#{{ $r->idpengadaan }}</a></td>
                            <td>{{ $r->nama_vendor }}</td>
                            <td>{{ $r->nama_user }}</td>
                            <td>{{ $r->jumlah_item }}</td>
                            <td>Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                            <td><a href="{{ route('penerimaan.show', $r->idpenerimaan) }}" class="btn btn-sm btn-outline-info">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Belum ada penerimaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penerimaan->links() }}
    </div>
@endsection
