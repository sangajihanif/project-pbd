@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Retur #' . $header->idretur,
            'action' => '<a href="' . route('retur.index') . '" class="btn btn-outline-secondary">Kembali</a>',
        ])

        <p>
            Tanggal: <strong>{{ $header->tanggal }}</strong> |
            Penerimaan: <a href="{{ route('penerimaan.show', $header->idpenerimaan) }}">#{{ $header->idpenerimaan }}</a> |
            Vendor: <strong>{{ $header->nama_vendor }}</strong> |
            Petugas: <strong>{{ $header->nama_user }}</strong>
        </p>

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center"><th>Barang</th><th>Satuan</th><th>Jumlah</th><th>Alasan</th></tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        <tr class="text-center">
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->nama_satuan }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>{{ $d->alasan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
