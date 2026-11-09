@extends('layouts.app')
@include('barang.search')
@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">Data Barang</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="Dashboards.Default.html">Home</a></li>
                            <li class="breadcrumb-item"><a href="Dashboards.html">Dashboards</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-sm-6 d-flex align-items-start justify-content-end">
                    <!-- Tour Button Start -->
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-end w-100 w-sm-auto"
                        data-bs-toggle="modal" data-bs-target="#createData">
                        <span>Tambah Barang</span>

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
                                <th scope="col">Jenis Barang</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Nama Satuan</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Status Barang</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $item)
                                <tr class="text-center">
                                    <th scope="row">{{ $item->idbarang }}</th>
                                    <td scope="row">{{ $item->jenis }}</td>
                                    <td scope="row">{{ $item->nama_barang }}</td>
                                    <td scope="row">{{ $item->nama_satuan }}</td>
                                    <td scope="row">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($item->status_text == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>


                                    
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                           
                                            <form action="{{ route('barang.status', $item->idbarang) }}" method="POST"
                                                class="d-inline m-0">
                                                @csrf
                                                @method('PATCH')

                                                @if ($item->status_text == 'Aktif')
                                                    <button type="submit" class="btn btn-sm btn-outline-warning"
                                                        title="Nonaktifkan Barang" style="width: 105px;">
                                                        <i class="bi bi-toggle-on"></i> Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        title="Aktifkan Barang" style="width: 105px;">
                                                        <i class="bi bi-toggle-off"></i> Aktifkan
                                                    </button>
                                                @endif
                                            </form>

                                          
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                style="width: 70px;" data-bs-toggle="modal"
                                                data-bs-target="#updateData{{ $item->idbarang }}">
                                                <span>Edit</span>
                                            </button>
                                            @include('layouts.partials.delete-button', [
                                                'action' => route('barang.destroy', $item->idbarang),
                                                'teks' => 'Hapus barang ' . $item->nama_barang . '?',
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
        @include('barang.modals.create')
        @include('barang.modals.update')
    @endpush
