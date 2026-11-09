@extends('layouts.auth.otp')

@section('content')
    <div id="root" class="h-100">
        <!-- Background Start -->
        <div class="fixed-background"></div>
        <!-- Background End -->

        <!-- Notifikasi Pesan Error / Gagal Validasi OTP -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
        @endif

        <!-- Notifikasi Pesan Sukses Kirim OTP -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
        @endif

        <div class="container-fluid p-0 h-100 position-relative">
            <div class="row g-0 h-100">
                <!-- Left Side Start (Tampilan Info Sisi Kiri) -->
                <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                    <div class="min-h-100 d-flex align-items-center">
                        <div class="w-100 w-lg-75 w-xxl-50">
                            <div>
                                <div class="mb-5">
                                    <h1 class="display-3 text-white">Security First</h1>
                                    <h1 class="display-3 text-white">Verify Your Account</h1>
                                </div>
                                <p class="h6 text-white lh-1-5 mb-5">
                                    Untuk melindungi keamanan akun Anda, kami telah mengirimkan kode verifikasi
                                    One-Time Password (OTP) ke alamat email terdaftar Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Left Side End -->

                <!-- Right Side Start (Form Input OTP Sisi Kanan) -->
                <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
                    <div
                        class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
                        <div class="sw-lg-50 px-5">
                            <div class="sh-11">
                                <a href="#">
                                    <div class="logo-default"></div>
                                </a>
                            </div>

                            <div class="mb-5">
                                <h2 class="cta-1 mb-0 text-primary">OTP Verification</h2>
                                <h2 class="cta-1 text-primary">Masukan Kode Anda</h2>
                            </div>

                            <div class="mb-5">
                                {{-- Menampilkan email terproteksi hasil hideEmail() --}}
                                <p class="h6">Kode verifikasi telah dikirim ke <strong>{{ $email }}</strong></p>
                                <p class="text-small text-muted">Silakan periksa kotak masuk Mailtrap Anda untuk menyalin 6
                                    digit kode OTP.</p>
                            </div>

                            <div>
                                {{-- Form Mengarah ke Route Pengecekan OTP --}}
                                <form action="{{ route('otp.verify', ['id' => $id]) }}" method="POST"
                                    class="tooltip-end-bottom" novalidate>
                                    @csrf

                                

                                    <div class="mb-4 d-flex justify-content-between gap-2 px-2" id="otp-container">
                                        <input type="text" name="first"
                                            class="form-control text-center font-weight-bold otp-field" maxlength="1"
                                            style="font-size: 1.5rem; width: 45px; height: 50px;" required autofocus />
                                        <input type="text" name="second"
                                            class="form-control text-center font-weight-bold otp-field" maxlength="1"
                                            style="font-size: 1.5rem; width: 45px; height: 50px;" required />
                                        <input type="text" name="third"
                                            class="form-control text-center font-weight-bold otp-field" maxlength="1"
                                            style="font-size: 1.5rem; width: 45px; height: 50px;" required />
                                        <input type="text" name="fourth"
                                            class="form-control text-center font-weight-bold otp-field" maxlength="1"
                                            style="font-size: 1.5rem; width: 45px; height: 50px;" required />
                                        <input type="text" name="fifth"
                                            class="form-control text-center font-weight-bold otp-field" maxlength="1"
                                            style="font-size: 1.5rem; width: 45px; height: 50px;" required />
                                        <input type="text" name="sixth"
                                            class="form-control text-center font-weight-bold otp-field" maxlength="1"
                                            style="font-size: 1.5rem; width: 45px; height: 50px;" required />
                                    </div>


                                    <button type="submit" class="btn btn-lg btn-primary w-100">Verifikasi Akun</button>
                                </form>
                                <div class="text-center mt-3">
                                    <p class="text-small text-muted mb-1">Tidak menerima kode verifikasi?</p>
                                    <a href="{{ route('otp.resend', ['id' => $id]) }}"
                                        class="btn btn-link p-0 text-primary font-weight-bold align-baseline">
                                        Kirim Ulang OTP
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Side End -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll(".otp-field");

            inputs.forEach((input, index) => {
               
                input.addEventListener("input", (e) => {
                    if (input.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && input.value.length === 0 && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });
    </script>
@endsection
