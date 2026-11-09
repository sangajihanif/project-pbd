@extends('layouts.app')
@include('vendor.search')
@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">Data Vendor</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="Dashboards.Default.html">Home</a></li>
                            <li class="breadcrumb-item"><a href="Dashboards.html">Vendor</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                    <!-- Tour Button Start -->
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#createData">
                        <span>Tambah Vendor</span>

                    </button>
                    <!-- Tour Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="table-responsive">
                    <table class="table table-hover table-dark">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Nama Vendor</th>
                                <th scope="col">Badan Hukum</th>
                                <th scope="col">Status Vendor</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $item)
                                <tr class="text-center">
                                    <th scope="row">{{ $item->idvendor }}</th>
                                    <td scope="row">{{ $item->nama_vendor }}</td>
                                    <td scope="row">{{ $item->badan_hukum_text }}</td>
                                    <td>
                                        @if ($item->status_text == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>


                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            <form action="{{ route('vendor.status', $item->idvendor) }}" method="POST"
                                                class="d-inline m-0">
                                                @csrf
                                                @method('PATCH')

                                                @if ($item->status_text == 'Aktif')
                                                    <button type="submit" class="btn btn-sm btn-outline-warning"
                                                        title="Nonaktifkan Vendor" style="width: 105px;">
                                                        <i class="bi bi-toggle-on"></i> Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        title="Aktifkan Vendor" style="width: 105px;">
                                                        <i class="bi bi-toggle-off"></i> Aktifkan
                                                    </button>
                                                @endif
                                            </form>


                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                style="width: 70px;" data-bs-toggle="modal"
                                                data-bs-target="#updateData{{ $item->idvendor }}">
                                                <span>Edit</span>
                                            </button>
                                            @include('layouts.partials.delete-button', [
                                                'action' => route('vendor.destroy', $item->idvendor),
                                                'teks' => 'Hapus vendor ' . $item->nama_vendor . '?',
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
        @include('vendor.modals.create')
        @include('vendor.modals.update')
    @endpush
