@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-sm-6">
                    <h1 class="mb-0 pb-0 display-4" id="title">Data User</h1>
                    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item"><a href="Dashboards.Default.html">Home</a></li>
                            <li class="breadcrumb-item"><a href="Dashboards.html">Dashboards</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- Title End -->
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
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $item)
                                <tr class="text-center">
                                    <th scope="row">{{ $item->iduser }}</th>
                                    <td scope="row">{{ $item->nama_user }}</td>
                                    <td scope="row">{{ $item->email }}</td>
                                    <td scope="row">{{ $item->nama_role }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                style="width: 70px;" data-bs-toggle="modal"
                                                data-bs-target="#updateData{{ $item->iduser }}">
                                                <span>Edit</span>
                                            </button>
                                            @include('layouts.partials.delete-button', [
                                                'action' => route('user.destroy', $item->iduser),
                                                'teks' => 'Hapus user ' . $item->nama_user . '?',
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
        @include('user.modals.update')
    @endpush
