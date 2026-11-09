<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\MarginPenjualan;
use App\Models\Role;
use App\Models\Satuan;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class seed_initial_data extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pengisian data Satuan
        collect([
            ['nama_satuan' => 'Pcs', 'status' => 1],
            ['nama_satuan' => 'Box', 'status' => 1],
            ['nama_satuan' => 'Lusin', 'status' => 1],
            ['nama_satuan' => 'Kg', 'status' => 1],
            ['nama_satuan' => 'Liter', 'status' => 0],
        ])->each(function ($satuan) {
            Satuan::create($satuan);
        });

        collect([
            [
                'nama_vendor' => 'PT Latern Tech',
                'badan_hukum' => 'Y',
                'status' => 'A',
            ]

        ])->each(function ($vendor) {
            Vendor::create($vendor);
        });

        collect([
            ['nama_role' => 'Super Admin'],
            ['nama_role' => 'Admin Gudang'],
            ['nama_role' => 'Kasir'],
            ['nama_role' => 'Tamu'],
            ['nama_role' => 'Admin Sistem'],
        ])->each(function ($role) {
            Role::create($role);
        });

        $roleId = DB::table('role')->where('nama_role', 'Super Admin')->value('idrole');

        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name'       => 'Super Admin',
                'password'   => Hash::make('password'),
                'idrole'     => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $userId = DB::table('users')->where('email', 'admin@example.com')->value('id');

        collect([
            ['persen' => 10, 'status' => 0, 'iduser' => $userId],
            ['persen' => 15, 'status' => 0, 'iduser' => $userId],
            ['persen' => 20, 'status' => 1, 'iduser' => $userId],
            ['persen' => 25, 'status' => 0, 'iduser' => $userId],
            ['persen' => 30, 'status' => 1, 'iduser' => $userId],
        ])->each(function ($margin) {
            MarginPenjualan::create($margin);
        });

        collect([
            ['jenis' => 'M', 'nama' => 'Beras Premium 5 Kg',    'idsatuan' => 4, 'status' => 1, 'harga' => 68000],
            ['jenis' => 'M', 'nama' => 'Minyak Goreng 2 Liter', 'idsatuan' => 5, 'status' => 1, 'harga' => 38000],
            ['jenis' => 'S', 'nama' => 'Sabun Mandi',           'idsatuan' => 1, 'status' => 1, 'harga' => 4500],
            ['jenis' => 'S', 'nama' => 'Pasta Gigi',            'idsatuan' => 1, 'status' => 1, 'harga' => 12000],
            ['jenis' => 'M', 'nama' => 'Air Mineral 600 ml',    'idsatuan' => 2, 'status' => 0, 'harga' => 42000],
        ])->each(function ($barang) {
            Barang::create($barang);
        });

        $idrole = DB::table('role')->where('nama_role', 'Kasir')->value('idrole');

        // Akun Kasir untuk uji coba 
        DB::table('users')->updateOrInsert(
            ['email' => 'kasir@example.com'],
            [
                'name'              => 'Kasir Satu',
                'password'          => Hash::make('password'),
                'idrole'            => $idrole,
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );

        // Stok awal 50 untuk setiap barang aktif yang belum punya kartu stok
        // (jenis_transaksi 'I' = stok awal)
        DB::statement("
            INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, idtransaksi, idbarang)
            SELECT 'I', 50, 0, 50, 0, b.idbarang
            FROM barang b
            WHERE b.status = 1
              AND NOT EXISTS (SELECT 1 FROM kartu_stok k WHERE k.idbarang = b.idbarang)
        ");
    }
}
