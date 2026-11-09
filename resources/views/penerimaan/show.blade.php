@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            $tombol = '<a href="' . route('pengadaan.show', $header->idpengadaan) . '" class="btn btn-outline-secondary me-2">Pengadaan</a>'
                    . '<a href="' . route('retur.create', $header->idpenerimaan) . '" class="btn btn-outline-warning">Buat Retur</a>';
        @endphp
        @include('layouts.partials.page-title', ['title' => 'Penerimaan #' . $header->idpenerimaan, 'action' => $tombol])

        <p>
            Tanggal: <strong>{{ $header->tanggal }}</strong> |
            Pengadaan: <strong>#{{ $header->idpengadaan }}</strong> |
            Vendor: <strong>{{ $header->nama_vendor }}</strong> |
            Petugas: <strong>{{ $header->nama_user }}</strong>
        </p>

        <div class="table-responsive mb-4">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>Barang</th><th>Satuan</th><th>Harga</th><th>Diterima</th><th>Subtotal</th><th>Bisa Diretur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr class="text-center">
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->nama_satuan }}</td>
                            <td>Rp {{ number_format($d->harga_satuan_terima, 0, ',', '.') }}</td>
                            <td>{{ $d->jumlah_terima }}</td>
                            <td>Rp {{ number_format($d->sub_total_terima, 0, ',', '.') }}</td>
                            <td>{{ $d->sisa_retur }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="mb-3">Retur dari Penerimaan Ini</h2>
        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center"><th>No.</th><th>Tanggal</th><th>Petugas</th><th>Total Barang</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($retur as $t)
                        <tr class="text-center">
                            <th scope="row">#{{ $t->idretur }}</th>
                            <td>{{ $t->tanggal }}</td>
                            <td>{{ $t->nama_user }}</td>
                            <td>{{ $t->total_barang }}</td>
                            <td><a href="{{ route('retur.show', $t->idretur) }}" class="btn btn-sm btn-outline-info">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada retur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
