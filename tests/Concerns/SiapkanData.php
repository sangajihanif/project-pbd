<?php

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Pembantu untuk menyiapkan data uji. Dipakai bersama DatabaseTransactions,
 * jadi semua data otomatis dibatalkan (rollback) setelah tiap tes selesai.
 */
trait SiapkanData
{
    protected function buatUser(int $idrole, string $nama = 'Uji'): User
    {
        static $n = 0;
        $n++;

        return User::forceCreate([
            'name'              => "$nama $n",
            'email'             => "uji{$n}_" . uniqid() . '@example.test',
            'password'          => Hash::make('password123'),
            'idrole'            => $idrole,
            'email_verified_at' => now(),
        ]);
    }

    protected function superAdmin(): User { return $this->buatUser(1, 'Admin'); }
    protected function gudang(): User     { return $this->buatUser(2, 'Gudang'); }
    protected function kasir(): User      { return $this->buatUser(3, 'Kasir'); }
    protected function tamu(): User       { return $this->buatUser(4, 'Tamu'); }

    protected function buatBarang(int $harga = 10000, int $stok = 50, int $status = 1): int
    {
        $idsatuan = DB::table('satuan')->insertGetId(['nama_satuan' => 'Uji ' . uniqid(), 'status' => 1]);

        $id = DB::table('barang')->insertGetId([
            'jenis' => 'M', 'nama' => 'Barang Uji ' . uniqid(), 'idsatuan' => $idsatuan,
            'status' => $status, 'harga' => $harga,
        ]);

        if ($stok > 0) {
            DB::table('kartu_stok')->insert([
                'jenis_transaksi' => 'I', 'masuk' => $stok, 'keluar' => 0, 'stock' => $stok,
                'idtransaksi' => 0, 'idbarang' => $id,
            ]);
        }

        return $id;
    }

    protected function buatVendor(string $status = 'A'): int
    {
        return DB::table('vendor')->insertGetId([
            'nama_vendor' => 'Vendor Uji ' . uniqid(), 'badan_hukum' => 'Y', 'status' => $status,
        ]);
    }

    /** Buat margin lalu jadikan satu-satunya yang aktif. */
    protected function aktifkanMargin(float $persen, int $iduser): int
    {
        $id = DB::table('margin_penjualan')->insertGetId([
            'persen' => $persen, 'status' => 0, 'iduser' => $iduser,
        ]);
        DB::statement('CALL sp_aktifkan_margin(?)', [$id]);

        return $id;
    }

    protected function stok(int $idbarang): int
    {
        return (int) DB::selectOne('SELECT fn_stok_barang(?) AS s', [$idbarang])->s;
    }
}
