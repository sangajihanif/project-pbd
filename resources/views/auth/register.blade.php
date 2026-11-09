@extends('layouts.auth.register')


@section('content')
    <div id="root" class="h-100">
        <!-- Background Start -->
        <div class="fixed-background"></div>
        <!-- Background End -->

        <div class="container-fluid p-0 h-100 position-relative">
            <div class="row g-0 h-100">
                <!-- Left Side Start -->
                <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                    <div class="min-h-100 d-flex align-items-center">
                        <div class="w-100 w-lg-75 w-xxl-50">
                            <div>
                                <div class="mb-5">
                                    <h1 class="display-3 text-white">Streamline Your Procurement & Inventory</h1>
                                </div>
                                <p class="h6 text-white lh-1-5 mb-5">
                                    Kelola alur pengadaan barang, verifikasi vendor, dan kontrol inventaris perusahaan secara transparan, akurat, dan terintegrasi dalam satu platform modern.
                                </p>
                                <div class="mb-5">
                                    <a class="btn btn-lg btn-outline-white" href="index.html">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Left Side End -->

                <!-- Right Side Start -->
                <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
                    <div
                        class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
                        <div class="sw-lg-50 px-5">
                            <div class="sh-11">
                                <a href="index.html">

                                </a>
                            </div>
                            <div class="mb-5">
                                <h2 class="cta-1 mb-0 text-primary">Welcome,</h2>
                                <h2 class="cta-1 text-primary">let's get the ball rolling!</h2>
                            </div>
                            <div class="mb-5">
                                <p class="h6">Please use the form to register.</p>
                                <p class="h6">
                                    If you are a member, please
                                    <a href="{{ route('login') }}">login</a>
                                    .
                                </p>
                            </div>
                            <div>
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger">{{ $error }}</div>
                                    @endforeach
                                @endif
                                <form action="{{ route('register.store') }}" method="POST" class="tooltip-end-bottom"
                                    novalidate>
                                    @csrf
                                    <div class="mb-3 filled form-group tooltip-end-top">
                                        <i data-acorn-icon="user"></i>
                                        <input type="text" class="form-control" placeholder="Name" name="name"
                                            value="{{ old('name') }}" />
                                    </div>
                                    <div
                                        class="mb-3
                                            filled form-group tooltip-end-top">
                                        <i data-acorn-icon="email"></i>
                                        <input type="email" class="form-control" placeholder="Email" name="email"
                                            value="{{ old('email') }}" />
                                    </div>
                                    <div class="mb-3 filled form-group tooltip-end-top">
                                        <i data-acorn-icon="lock-on"></i>
                                        <input class="form-control" name="password" type="password"
                                            placeholder="Password" />
                                    </div>
                                    <div class="mb-3 filled form-group tooltip-end-top">
                                        <i data-acorn-icon="lock-on"></i>
                                        <input class="form-control" name="password_confirmation" type="password"
                                            placeholder="Confirmed Passowrd" />
                                    </div>
                                    <div class="mb-3 position-relative form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="registerCheck"
                                                name="registerCheck" />
                                            <label class="form-check-label" for="registerCheck">
                                                I have read and accept the
                                                <a href="index.html" target="_blank">terms and conditions.</a>
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-primary">Signup</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Side End -->
            </div>
        </div>
    </div>
@endsection
