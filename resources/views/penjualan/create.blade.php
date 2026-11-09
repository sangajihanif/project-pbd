@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Transaksi Penjualan Baru'])

        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover table-dark">
                    <thead>
                        <tr class="text-center">
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th style="width: 140px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $b)
                            <tr class="text-center">
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->nama_satuan }}</td>
                                <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $b->stok }}</td>
                                <td>
                                    <input type="number" name="items[{{ $b->idbarang }}]" min="0"
                                        max="{{ $b->stok }}" value="{{ old('items.' . $b->idbarang, 0) }}"
                                        class="form-control text-center">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Tidak ada barang yang tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @error('items')
                <div class="text-danger mb-2">{{ $message }}</div>
            @enderror

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
            <p class="text-muted mt-3 mb-0">PPN 11% dan total dihitung otomatis oleh database.</p>
        </form>
    </div>
@endsection
