@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">Data Margin Penjualan</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="Dashboards.Default.html">Home</a></li>
                            <li class="breadcrumb-item"><a href="Dashboards.html">Dashboards</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->

                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                    <!-- Tour Button Start -->
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#createData">
                        <span>Tambah Margin</span>

                    </button>
                    <!-- Tour Button End -->
                </div>
            </div>

        </div>
        <!-- Title and Top Buttons End 'name', 'email', 'password', 'idrole' -->

        @include('layouts.partials.flash')

        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="table-responsive">
                    <table class="table table-hover table-dark">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Nama User</th>
                                <th scope="col">Persen</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($margins as $item)
                                <tr class="text-center">
                                    <th scope="row">{{ $item->idmargin_penjualan }}</th>
                                    <td scope="row">{{ $item->nama_user }}</td>
                                    <td scope="row">{{ $item->persen }}</td>
                                    <td scope="row">
                                        @if ($item->status_text == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            <form action="{{ route('margin.status', $item->idmargin_penjualan) }}"
                                                method="POST" class="d-inline m-0">
                                                @csrf
                                                @method('PATCH')

                                                @if ($item->status_text == 'Aktif')
                                                    <button type="submit" class="btn btn-sm btn-outline-warning"
                                                        title="Nonaktifkan Margin" style="width: 105px;">
                                                        <i class="bi bi-toggle-on"></i> Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        title="Aktifkan Margin" style="width: 105px;">
                                                        <i class="bi bi-toggle-off"></i> Aktifkan
                                                    </button>
                                                @endif
                                            </form>


                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                style="width: 70px;" data-bs-toggle="modal"
                                                data-bs-target="#updateData{{ $item->idmargin_penjualan }}">
                                                <span>Edit</span>
                                            </button>
                                            @include('layouts.partials.delete-button', [
                                                'action' => route('margin.destroy', $item->idmargin_penjualan),
                                                'teks' => 'Hapus margin ' . $item->persen . '%?',
                                            ])
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>



            <!-- Banners Start -->

            <!-- Banners End -->


        </div>
    @endsection

    @push('modals')
        @include('margin.modals.create')
        @include('margin.modals.update')
    @endpush
