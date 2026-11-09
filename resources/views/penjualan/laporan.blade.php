@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Laporan Penjualan per Kasir'])

        <form action="{{ route('penjualan.laporan') }}" method="GET" class="row g-2 mb-4">
            <div class="col-auto">
                <label class="form-label">Dari</label>
                <input type="date" name="dari" value="{{ $dari }}" class="form-control">
            </div>
            <div class="col-auto">
                <label class="form-label">Sampai</label>
                <input type="date" name="sampai" value="{{ $sampai }}" class="form-control">
            </div>
            <div class="col-auto align-self-end">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>

        @error('sampai')
            <div class="text-danger mb-2">{{ $message }}</div>
        @enderror

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>Kasir</th><th>Transaksi</th><th>Subtotal</th><th>PPN</th><th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $r)
                        <tr class="text-center">
                            <td>{{ $r->kasir }}</td>
                            <td>{{ $r->jumlah_transaksi }}</td>
                            <td>Rp {{ number_format($r->total_subtotal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($r->total_ppn, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($r->total_penjualan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
