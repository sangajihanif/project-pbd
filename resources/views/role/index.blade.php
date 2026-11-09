@extends('layouts.app')
@include('role.search')
@section('content')
    <div class="container">
        @include('layouts.partials.page-title', [
            'title' => 'Data Role',
            'action' => '<button type="button" class="btn btn-outline-primary w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#createData">Tambah Role</button>',
        ])

        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Nama Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($role as $item)
                        <tr class="text-center">
                            <th scope="row">{{ $item->idrole }}</th>
                            <td>{{ $item->nama_role }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-warning" style="width: 70px;"
                                        data-bs-toggle="modal" data-bs-target="#updateData{{ $item->idrole }}">Edit</button>
                                    @if ($item->idrole > 5)
                                        @include('layouts.partials.delete-button', [
                                            'action' => route('role.destroy', $item->idrole),
                                            'teks' => 'Hapus role ' . $item->nama_role . '?',
                                        ])
                                    @else
                                        <span class="badge bg-secondary" title="Role bawaan sistem">Bawaan</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
    @include('role.modals.create')
    @include('role.modals.update')
@endpush
