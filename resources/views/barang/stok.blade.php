@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Stok & Harga Barang'])

        <div class="table-responsive mb-5">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>#</th><th>Jenis</th><th>Nama Barang</th><th>Satuan</th>
                        <th>Harga Dasar</th><th>Harga Jual</th><th>Stok</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barang as $item)
                        <tr class="text-center">
                            <th scope="row">{{ $item->idbarang }}</th>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td>Rp {{ number_format($item->harga_dasar, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>
                                <span class="badge {{ $item->status_text == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->status_text }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="mb-3">Kartu Stok Terakhir</h2>
        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>Waktu</th><th>Barang</th><th>Jenis</th><th>Masuk</th><th>Keluar</th><th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kartu as $k)
                        <tr class="text-center">
                            <td>{{ $k->created_at }}</td>
                            <td>{{ $k->nama_barang }}</td>
                            <td>{{ $k->jenis_text }}</td>
                            <td>{{ $k->masuk }}</td>
                            <td>{{ $k->keluar }}</td>
                            <td>{{ $k->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
