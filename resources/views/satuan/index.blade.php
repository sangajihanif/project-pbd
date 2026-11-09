@extends('layouts.app')
@include('satuan.search')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Data Satuan',
            'action' => '<button type="button" class="btn btn-outline-primary w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#createData">Tambah Satuan</button>',
        ])

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Nama Satuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($satuan as $item)
                        <tr class="text-center">
                            <th scope="row">{{ $item->idsatuan }}</th>
                            <td>{{ $item->nama_satuan }}</td>
                            <td>
                                <span class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <form action="{{ route('satuan.status', $item->idsatuan) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $item->status ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            style="width: 105px;">{{ $item->status ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-warning" style="width: 70px;"
                                        data-bs-toggle="modal" data-bs-target="#updateData{{ $item->idsatuan }}">Edit</button>
                                    @include('layouts.partials.delete-button', [
                                        'action' => route('satuan.destroy', $item->idsatuan),
                                        'teks' => 'Hapus satuan ' . $item->nama_satuan . '?',
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada satuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $satuan->links() }}
    </div>
@endsection

@push('modals')
    @include('satuan.modals.create')
    @include('satuan.modals.update')
@endpush
