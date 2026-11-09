<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');


Route::get('/register',  [UserController::class, 'register'])->name('register');
Route::post('/register',  [UserController::class, 'storeRegister'])->name('register.store');
Route::get('/login',  [UserController::class, 'login'])->name('login');
Route::post('/login',  [UserController::class, 'LoginStore'])->name('login.store')->middleware('throttle:5,1');

Route::get('/otp/{id}',  [UserController::class, 'viewOtp'])->name('otp');
Route::get('/otp/{id}/resend',  [UserController::class, 'resendOtp'])->name('otp.resend')->middleware('throttle:3,1');
Route::post('/otp/{id}',  [UserController::class, 'validateOtp'])->name('otp.verify')->middleware('throttle:5,1');


Route::middleware('auth')->group(function () {
    Route::post('logout', [UserController::class, 'logout'])->name('logout');


    Route::middleware(['account.verified'])->group(function () {

        
        // SEMUA ROLE (tampilan menyesuaikan role di controller)
        
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

        
        // SUPER ADMIN (role 1): master data, user, margin, laporan
        
        Route::middleware(['role:1'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('/user', [UserController::class, 'index'])->name('user.index');
            Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
            Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

            Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
            Route::put('/barang/{id}/update', [BarangController::class, 'update'])->name('barang.update');
            Route::patch('/barang/{barang}/status', [BarangController::class, 'toggleStatus'])->name('barang.status');
            Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

            Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
            Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
            Route::put('/satuan/{id}/update', [SatuanController::class, 'update'])->name('satuan.update');
            Route::patch('/satuan/{satuan}/status', [SatuanController::class, 'toggleStatus'])->name('satuan.status');
            Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

            Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
            Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
            Route::put('/vendor/{id}/update', [VendorController::class, 'update'])->name('vendor.update');
            Route::patch('/vendor/{vendor}/status', [VendorController::class, 'toggleStatus'])->name('vendor.status');
            Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

            Route::get('/role', [RoleController::class, 'index'])->name('role.index');
            Route::post('/role', [RoleController::class, 'store'])->name('role.store');
            Route::put('/role/{id}/update', [RoleController::class, 'update'])->name('role.update');
            Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

            Route::get('/margin-penjualan', [MarginPenjualanController::class, 'index'])->name('margin.index');
            Route::post('/margin-penjualan', [MarginPenjualanController::class, 'store'])->name('margin.store');
            Route::put('/margin-penjualan/{id}/update', [MarginPenjualanController::class, 'update'])->name('margin.update');
            Route::patch('/margin-penjualan/{margin}/status', [MarginPenjualanController::class, 'toggleStatus'])->name('margin.status');
            Route::delete('/margin-penjualan/{id}', [MarginPenjualanController::class, 'destroy'])->name('margin.destroy');

            Route::get('/laporan-penjualan', [PenjualanController::class, 'laporan'])->name('penjualan.laporan');
        });

        
        // SUPER ADMIN + ADMIN GUDANG (role 1,2): stok & modul gudang
        
        Route::middleware(['role:1,2'])->group(function () {
            Route::get('/stok', [BarangController::class, 'stok'])->name('stok.index');

            // Pengadaan (pesan barang ke vendor)
            Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
            Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
            Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
            Route::get('/pengadaan/{id}', [PengadaanController::class, 'show'])->whereNumber('id')->name('pengadaan.show');
            Route::delete('/pengadaan/{id}', [PengadaanController::class, 'destroy'])->whereNumber('id')->name('pengadaan.destroy');

            // Penerimaan (barang dari vendor datang) - dibuat dari halaman pengadaan
            Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
            Route::get('/penerimaan/create/{pengadaan}', [PenerimaanController::class, 'create'])->whereNumber('pengadaan')->name('penerimaan.create');
            Route::post('/penerimaan/{pengadaan}', [PenerimaanController::class, 'store'])->whereNumber('pengadaan')->name('penerimaan.store');
            Route::get('/penerimaan/{id}', [PenerimaanController::class, 'show'])->whereNumber('id')->name('penerimaan.show');

            // Retur (kembalikan barang ke vendor) - dibuat dari halaman penerimaan
            Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
            Route::get('/retur/create/{penerimaan}', [ReturController::class, 'create'])->whereNumber('penerimaan')->name('retur.create');
            Route::post('/retur/{penerimaan}', [ReturController::class, 'store'])->whereNumber('penerimaan')->name('retur.store');
            Route::get('/retur/{id}', [ReturController::class, 'show'])->whereNumber('id')->name('retur.show');
        });

        
        // KASIR (role 3): membuat transaksi
        // (/penjualan/create harus di atas /penjualan/{id})
        
        Route::middleware(['role:3'])->group(function () {
            Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
            Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
        });

        
        // SUPER ADMIN + KASIR: melihat penjualan
        // (Kasir hanya melihat miliknya sendiri, diatur di controller)
        
        Route::middleware(['role:1,3'])->group(function () {
            Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
            Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        });
    });
});
