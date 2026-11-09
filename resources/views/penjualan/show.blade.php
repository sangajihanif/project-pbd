@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Transaksi #' . $header->idpenjualan,
            'action' => '<a href="' . route('penjualan.index') . '" class="btn btn-outline-secondary">Kembali</a>',
        ])

        <p>
            Waktu: <strong>{{ $header->created_at }}</strong>
            @if ($isAdmin)
                | Kasir: <strong>{{ $header->nama_kasir }}</strong>
                | Margin: <strong>{{ $header->margin_persen }}%</strong>
            @endif
        </p>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>Barang</th><th>Satuan</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr class="text-center">
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->nama_satuan }}</td>
                            <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-end"><td colspan="4">Subtotal</td><td class="text-center">Rp {{ number_format($header->subtotal_nilai, 0, ',', '.') }}</td></tr>
                    <tr class="text-end"><td colspan="4">PPN (11%)</td><td class="text-center">Rp {{ number_format($header->ppn, 0, ',', '.') }}</td></tr>
                    <tr class="text-end"><th colspan="4">Total</th><th class="text-center">Rp {{ number_format($header->total_nilai, 0, ',', '.') }}</th></tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
