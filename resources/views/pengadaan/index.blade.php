@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Pengadaan Barang',
            'action' => '<a href="' . route('pengadaan.create') . '" class="btn btn-outline-primary w-100 w-sm-auto">Pengadaan Baru</a>',
        ])

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>No.</th><th>Tanggal</th><th>Vendor</th><th>Petugas</th>
                        <th>Item</th><th>Total</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengadaan as $p)
                        <tr class="text-center">
                            <th scope="row">#{{ $p->idpengadaan }}</th>
                            <td>{{ $p->tanggal }}</td>
                            <td>{{ $p->nama_vendor }}</td>
                            <td>{{ $p->nama_user }}</td>
                            <td>{{ $p->jumlah_item }}</td>
                            <td>Rp {{ number_format($p->total_nilai, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $p->status == 'S' ? 'bg-success' : 'bg-warning' }}">{{ $p->status_text }}</span>
                            </td>
                            <td>
                                <a href="{{ route('pengadaan.show', $p->idpengadaan) }}" class="btn btn-sm btn-outline-info">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Belum ada pengadaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pengadaan->links() }}
    </div>
@endsection
