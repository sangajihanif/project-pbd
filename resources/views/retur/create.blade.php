@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Retur - Penerimaan #' . $header->idpenerimaan,
            'action' => '<a href="' . route('penerimaan.show', $header->idpenerimaan) . '" class="btn btn-outline-secondary">Kembali</a>',
        ])

        <p>Vendor: <strong>{{ $header->nama_vendor }}</strong></p>

        <form action="{{ route('retur.store', $header->idpenerimaan) }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover table-dark">
                    <thead>
                        <tr class="text-center">
                            <th>Barang</th><th>Satuan</th><th>Diterima</th><th>Bisa Diretur</th>
                            <th style="width:130px;">Jumlah Retur</th><th style="width:260px;">Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $d)
                            <tr class="text-center">
                                <td>{{ $d->nama_barang }}</td>
                                <td>{{ $d->nama_satuan }}</td>
                                <td>{{ $d->jumlah_terima }}</td>
                                <td>{{ $d->sisa_retur }}</td>
                                <td>
                                    <input type="number" min="0" max="{{ $d->sisa_retur }}" class="form-control text-center"
                                        name="items[{{ $d->iddetail_penerimaan }}][jumlah]"
                                        value="{{ old('items.' . $d->iddetail_penerimaan . '.jumlah', 0) }}">
                                </td>
                                <td>
                                    <input type="text" maxlength="200" class="form-control" placeholder="mis. kemasan rusak"
                                        name="items[{{ $d->iddetail_penerimaan }}][alasan]"
                                        value="{{ old('items.' . $d->iddetail_penerimaan . '.alasan') }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Retur</button>
                <a href="{{ route('penerimaan.show', $header->idpenerimaan) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
            <p class="text-muted mt-3 mb-0">Alasan wajib diisi untuk barang yang diretur. Stok berkurang otomatis.</p>
        </form>
    </div>
@endsection
