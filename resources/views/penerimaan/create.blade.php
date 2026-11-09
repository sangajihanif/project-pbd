@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Terima Barang - Pengadaan #' . $header->idpengadaan,
            'action' => '<a href="' . route('pengadaan.show', $header->idpengadaan) . '" class="btn btn-outline-secondary">Kembali</a>',
        ])

        <p>Vendor: <strong>{{ $header->nama_vendor }}</strong></p>

        <form action="{{ route('penerimaan.store', $header->idpengadaan) }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover table-dark">
                    <thead>
                        <tr class="text-center">
                            <th>Barang</th><th>Satuan</th><th>Harga</th><th>Dipesan</th><th>Sisa</th><th style="width:150px;">Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $d)
                            <tr class="text-center">
                                <td>{{ $d->nama_barang }}</td>
                                <td>{{ $d->nama_satuan }}</td>
                                <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                <td>{{ $d->jumlah }}</td>
                                <td>{{ $d->sisa }}</td>
                                <td>
                                    <input type="number" min="0" max="{{ $d->sisa }}" class="form-control text-center"
                                        name="items[{{ $d->idbarang }}]"
                                        value="{{ old('items.' . $d->idbarang, $d->sisa) }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Penerimaan</button>
                <a href="{{ route('pengadaan.show', $header->idpengadaan) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
            <p class="text-muted mt-3 mb-0">Jumlah awal terisi sebesar sisa pesanan. Ubah bila barang datang sebagian. Stok bertambah otomatis.</p>
        </form>
    </div>
@endsection
