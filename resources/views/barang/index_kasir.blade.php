@extends('layouts.app')
@include('barang.search')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Daftar Barang'])

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $item)
                        <tr class="text-center">
                            <th scope="row">{{ $item->idbarang }}</th>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->stok > 0)
                                    {{ $item->stok }}
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $barang->links() }}
    </div>
@endsection
