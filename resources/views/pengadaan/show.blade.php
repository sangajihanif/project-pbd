@extends('layouts.app')
@section('content')
    <div class="container">
        @php
            $tombol = '<a href="' . route('pengadaan.index') . '" class="btn btn-outline-secondary me-2">Kembali</a>';
            if ($header->status == 'P') {
                $tombol .= '<a href="' . route('penerimaan.create', $header->idpengadaan) . '" class="btn btn-primary">Terima Barang</a>';
            }
        @endphp
        @include('layouts.partials.page-title', ['title' => 'Pengadaan #' . $header->idpengadaan, 'action' => $tombol])

        <p>
            Tanggal: <strong>{{ $header->tanggal }}</strong> |
            Vendor: <strong>{{ $header->nama_vendor }}</strong> |
            Petugas: <strong>{{ $header->nama_user }}</strong> |
            Status: <span class="badge {{ $header->status == 'S' ? 'bg-success' : 'bg-warning' }}">{{ $header->status_text }}</span>
        </p>

        <div class="table-responsive mb-4">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>Barang</th><th>Satuan</th><th>Harga</th><th>Dipesan</th><th>Diterima</th><th>Sisa</th><th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr class="text-center">
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->nama_satuan }}</td>
                            <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>{{ $d->diterima }}</td>
                            <td>{{ $d->sisa }}</td>
                            <td>Rp {{ number_format($d->sub_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-end"><td colspan="6">Subtotal</td><td class="text-center">Rp {{ number_format($header->subtotal_nilai, 0, ',', '.') }}</td></tr>
                    <tr class="text-end"><td colspan="6">PPN (11%)</td><td class="text-center">Rp {{ number_format($header->ppn, 0, ',', '.') }}</td></tr>
                    <tr class="text-end"><th colspan="6">Total</th><th class="text-center">Rp {{ number_format($header->total_nilai, 0, ',', '.') }}</th></tr>
                </tfoot>
            </table>
        </div>

        <h2 class="mb-3">Riwayat Penerimaan</h2>
        <div class="table-responsive mb-4">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center"><th>No.</th><th>Tanggal</th><th>Petugas</th><th>Item</th><th>Nilai</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($penerimaan as $r)
                        <tr class="text-center">
                            <th scope="row">#{{ $r->idpenerimaan }}</th>
                            <td>{{ $r->tanggal }}</td>
                            <td>{{ $r->nama_user }}</td>
                            <td>{{ $r->jumlah_item }}</td>
                            <td>Rp {{ number_format($r->total_nilai, 0, ',', '.') }}</td>
                            <td><a href="{{ route('penerimaan.show', $r->idpenerimaan) }}" class="btn btn-sm btn-outline-info">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada penerimaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($penerimaan->isEmpty())
            @include('layouts.partials.delete-button', [
                'action' => route('pengadaan.destroy', $header->idpengadaan),
                'teks' => 'Hapus pengadaan #' . $header->idpengadaan . '?',
            ])
        @endif
    </div>
@endsection
