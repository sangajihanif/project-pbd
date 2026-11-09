@extends('layouts.app')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', ['title' => 'Pengadaan Baru'])

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('pengadaan.store') }}" method="POST">
            @csrf

            <div class="form-group mb-4" style="max-width: 420px;">
                <label for="idvendor" class="form-label">Vendor</label>
                <select name="idvendor" id="idvendor" class="form-select" required>
                    <option value="" disabled @selected(!old('idvendor'))>-- Pilih Vendor --</option>
                    @foreach ($vendor as $v)
                        <option value="{{ $v->idvendor }}" @selected(old('idvendor') == $v->idvendor)>{{ $v->nama_vendor }}</option>
                    @endforeach
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-dark">
                    <thead>
                        <tr class="text-center">
                            <th>Barang</th><th>Satuan</th><th style="width:180px;">Harga Beli</th><th style="width:140px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang as $b)
                            <tr class="text-center">
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->nama_satuan }}</td>
                                <td>
                                    <input type="number" min="0" class="form-control text-center"
                                        name="items[{{ $b->idbarang }}][harga]"
                                        value="{{ old('items.' . $b->idbarang . '.harga', $b->harga) }}">
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control text-center"
                                        name="items[{{ $b->idbarang }}][jumlah]"
                                        value="{{ old('items.' . $b->idbarang . '.jumlah', 0) }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Pengadaan</button>
                <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
            <p class="text-muted mt-3 mb-0">Isi jumlah hanya pada barang yang dipesan. Subtotal, PPN 11%, dan total dihitung otomatis oleh database.</p>
        </form>
    </div>
@endsection
