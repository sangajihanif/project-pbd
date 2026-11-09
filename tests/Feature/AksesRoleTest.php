<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\SiapkanData;
use Tests\TestCase;

class AksesRoleTest extends TestCase
{
    use DatabaseTransactions, SiapkanData;

    /** url => [Super Admin, Admin Gudang, Kasir, Tamu] */
    public static function matriks(): array
    {
        return [
            '/dashboard'         => ['/dashboard',         [200, 403, 403, 403]],
            '/barang'            => ['/barang',            [200, 200, 200, 200]],
            '/stok'              => ['/stok',              [200, 200, 403, 403]],
            '/satuan'            => ['/satuan',            [200, 403, 403, 403]],
            '/vendor'            => ['/vendor',            [200, 403, 403, 403]],
            '/margin-penjualan'  => ['/margin-penjualan',  [200, 403, 403, 403]],
            '/user'              => ['/user',              [200, 403, 403, 403]],
            '/role'              => ['/role',              [200, 403, 403, 403]],
            '/pengadaan'         => ['/pengadaan',         [200, 200, 403, 403]],
            '/pengadaan/create'  => ['/pengadaan/create',  [200, 200, 403, 403]],
            '/penerimaan'        => ['/penerimaan',        [200, 200, 403, 403]],
            '/retur'             => ['/retur',             [200, 200, 403, 403]],
            '/penjualan'         => ['/penjualan',         [200, 403, 200, 403]],
            '/penjualan/create'  => ['/penjualan/create',  [403, 403, 200, 403]],
            '/laporan-penjualan' => ['/laporan-penjualan', [200, 403, 403, 403]],
        ];
    }

    #[DataProvider('matriks')]
    public function test_hak_akses_per_role(string $url, array $harapan): void
    {
        $users = [$this->superAdmin(), $this->gudang(), $this->kasir(), $this->tamu()];
        $nama  = ['Super Admin', 'Admin Gudang', 'Kasir', 'Tamu'];

        foreach ($users as $i => $user) {
            $this->actingAs($user)->get($url)->assertStatus($harapan[$i], "{$nama[$i]} membuka $url");
        }
    }

    public function test_tamu_belum_login_diarahkan_ke_login(): void
    {
        foreach (['/dashboard', '/barang', '/penjualan', '/pengadaan'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_halaman_barang_beda_tampilan_per_role(): void
    {
        $id = $this->buatBarang(harga: 10000, stok: 5);
        $nama = \DB::table('barang')->where('idbarang', $id)->value('nama');

        // Kasir: tidak ada kolom harga dasar / status
        $this->actingAs($this->kasir())->get('/barang?q=' . urlencode($nama))
            ->assertOk()->assertSee($nama)->assertDontSee('Harga Dasar');

        // Super Admin: halaman stok menampilkan harga dasar
        $this->actingAs($this->superAdmin())->get('/stok')->assertOk()->assertSee('Harga Dasar');
    }
}
