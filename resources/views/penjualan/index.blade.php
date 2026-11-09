@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            $tombol = $isAdmin
                ? '<a href="' . route('penjualan.laporan') . '" class="btn btn-outline-primary w-100 w-sm-auto">Laporan Penjualan</a>'
                : '<a href="' . route('penjualan.create') . '" class="btn btn-outline-primary w-100 w-sm-auto">Transaksi Baru</a>';
        @endphp
        @include('layouts.partials.page-title', [
            'title' => $isAdmin ? 'Semua Penjualan' : 'Penjualan Saya',
            'action' => $tombol,
        ])

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>No. Transaksi</th>
                        <th>Waktu</th>
                        @if ($isAdmin)
                            <th>Kasir</th>
                            <th>Margin</th>
                        @endif
                        <th>Item</th>
                        <th>Subtotal</th>
                        <th>PPN</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penjualan as $p)
                        <tr class="text-center">
                            <th scope="row">#{{ $p->idpenjualan }}</th>
                            <td>{{ $p->created_at }}</td>
                            @if ($isAdmin)
                                <td>{{ $p->nama_kasir }}</td>
                                <td>{{ $p->margin_persen }}%</td>
                            @endif
                            <td>{{ $p->jumlah_item }}</td>
                            <td>Rp {{ number_format($p->subtotal_nilai, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($p->ppn, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($p->total_nilai, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('penjualan.show', $p->idpenjualan) }}"
                                    class="btn btn-sm btn-outline-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $isAdmin ? 9 : 7 }}" class="text-center">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penjualan->links() }}
    </div>
@endsection
