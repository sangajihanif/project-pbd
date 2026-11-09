{{-- Menu sidebar sesuai role. Dipanggil dari layouts/navbar.blade.php di dalam <ul id="menu"> --}}
@php $role = (int) auth()->user()->idrole; @endphp

@if ($role === 1)
    {{-- SUPER ADMIN --}}
    <li><a href="{{ route('dashboard') }}"><span class="label">Dashboard</span></a></li>
    <li><a href="{{ route('barang.index') }}"><span class="label">Barang</span></a></li>
    <li><a href="{{ route('satuan.index') }}"><span class="label">Satuan</span></a></li>
    <li><a href="{{ route('stok.index') }}"><span class="label">Stok &amp; Harga</span></a></li>
    <li><a href="{{ route('vendor.index') }}"><span class="label">Vendor</span></a></li>
    <li><a href="{{ route('margin.index') }}"><span class="label">Margin Penjualan</span></a></li>
    <li><a href="{{ route('pengadaan.index') }}"><span class="label">Pengadaan</span></a></li>
    <li><a href="{{ route('penerimaan.index') }}"><span class="label">Penerimaan</span></a></li>
    <li><a href="{{ route('retur.index') }}"><span class="label">Retur</span></a></li>
    <li><a href="{{ route('penjualan.index') }}"><span class="label">Penjualan</span></a></li>
    <li><a href="{{ route('penjualan.laporan') }}"><span class="label">Laporan Penjualan</span></a></li>
    <li><a href="{{ route('user.index') }}"><span class="label">User</span></a></li>
    <li><a href="{{ route('role.index') }}"><span class="label">Role</span></a></li>
@elseif ($role === 2)
    {{-- ADMIN GUDANG --}}
    <li><a href="{{ route('pengadaan.index') }}"><span class="label">Pengadaan</span></a></li>
    <li><a href="{{ route('penerimaan.index') }}"><span class="label">Penerimaan</span></a></li>
    <li><a href="{{ route('retur.index') }}"><span class="label">Retur</span></a></li>
    <li><a href="{{ route('stok.index') }}"><span class="label">Stok &amp; Harga</span></a></li>
    <li><a href="{{ route('barang.index') }}"><span class="label">Daftar Barang</span></a></li>
@elseif ($role === 3)
    {{-- KASIR --}}
    <li><a href="{{ route('penjualan.create') }}"><span class="label">Transaksi Baru</span></a></li>
    <li><a href="{{ route('penjualan.index') }}"><span class="label">Penjualan Saya</span></a></li>
    <li><a href="{{ route('barang.index') }}"><span class="label">Daftar Barang</span></a></li>
@else
    <li><a href="{{ route('barang.index') }}"><span class="label">Daftar Barang</span></a></li>
@endif
