<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\SiapkanData;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use DatabaseTransactions, SiapkanData;

    private $admin;
    private $kasir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->superAdmin();
        $this->kasir = $this->kasir();
        $this->aktifkanMargin(20, $this->admin->id);
    }

    public function test_function_harga_jual_dan_stok(): void
    {
        $id = $this->buatBarang(harga: 10000, stok: 50);

        $this->assertSame(12000, (int) DB::selectOne('SELECT fn_harga_jual(?) AS h', [$id])->h); // 10000 + 20%
        $this->assertSame(50, $this->stok($id));
        $this->assertSame(0, $this->stok(999999)); // tanpa kartu stok
    }

    public function test_transaksi_kasir_menghitung_total_dan_mengurangi_stok(): void
    {
        $id = $this->buatBarang(harga: 10000, stok: 50);

        $res = $this->actingAs($this->kasir)->post('/penjualan', ['items' => [$id => 3]]);
        $res->assertSessionHasNoErrors()->assertSessionHas('success');

        $p = DB::table('penjualan')->where('iduser', $this->kasir->id)->first();
        $this->assertNotNull($p);
        $this->assertSame(36000, (int) $p->subtotal_nilai);   // 3 x 12000
        $this->assertSame(3960, (int) $p->ppn);                // 11%
        $this->assertSame(39960, (int) $p->total_nilai);

        $this->assertSame(47, $this->stok($id));
        $this->assertDatabaseHas('kartu_stok', [
            'idbarang' => $id, 'jenis_transaksi' => 'J', 'keluar' => 3, 'stock' => 47, 'idtransaksi' => $p->idpenjualan,
        ]);

        // halaman detail tampil
        $this->actingAs($this->kasir)->get("/penjualan/{$p->idpenjualan}")
            ->assertOk()->assertSee('39.960');
    }

    public function test_stok_tidak_cukup_ditolak_dan_semua_dibatalkan(): void
    {
        $a = $this->buatBarang(stok: 10);
        $b = $this->buatBarang(stok: 2);
        $sebelum = DB::table('penjualan')->count();

        // barang A cukup, barang B kurang -> seluruh transaksi harus batal
        $this->actingAs($this->kasir)->post('/penjualan', ['items' => [$a => 5, $b => 99]])
            ->assertSessionHas('error');

        $this->assertSame($sebelum, DB::table('penjualan')->count());
        $this->assertSame(10, $this->stok($a)); // barang A tidak ikut berkurang
        $this->assertSame(2, $this->stok($b));
    }

    public function test_input_kosong_dan_barang_nonaktif_ditolak(): void
    {
        $nonaktif = $this->buatBarang(stok: 10, status: 0);

        $this->actingAs($this->kasir)->post('/penjualan', ['items' => [1 => 0]])->assertSessionHas('error');
        $this->actingAs($this->kasir)->post('/penjualan', ['items' => [$nonaktif => 1]])->assertSessionHas('error');
        $this->assertSame(10, $this->stok($nonaktif));
    }

    public function test_trigger_menolak_insert_langsung_melebihi_stok(): void
    {
        $id = $this->buatBarang(stok: 5);
        DB::statement('CALL sp_buat_penjualan(?, @pid)', [$this->kasir->id]);
        $pid = DB::selectOne('SELECT @pid AS id')->id;

        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('Stok barang tidak mencukupi');

        DB::table('detail_penjualan')->insert([
            'harga_satuan' => 1000, 'jumlah' => 6, 'subtotal' => 0,
            'penjualan_idpenjualan' => $pid, 'idbarang' => $id,
        ]);
    }

    public function test_kasir_hanya_melihat_penjualan_miliknya(): void
    {
        $id = $this->buatBarang(stok: 20);
        $kasir2 = $this->kasir();

        $this->actingAs($this->kasir)->post('/penjualan', ['items' => [$id => 1]]);
        $this->actingAs($kasir2)->post('/penjualan', ['items' => [$id => 2]]);

        $punya1 = DB::table('penjualan')->where('iduser', $this->kasir->id)->value('idpenjualan');
        $punya2 = DB::table('penjualan')->where('iduser', $kasir2->id)->value('idpenjualan');

        $this->actingAs($this->kasir)->get("/penjualan/$punya1")->assertOk();
        $this->actingAs($this->kasir)->get("/penjualan/$punya2")->assertNotFound();

        $this->actingAs($this->kasir)->get('/penjualan')->assertOk()
            ->assertSee("#$punya1")->assertDontSee("#$punya2");

        // Super Admin melihat semuanya, plus kolom margin
        $this->actingAs($this->admin)->get('/penjualan')->assertOk()
            ->assertSee("#$punya1")->assertSee("#$punya2")->assertSee('Margin');
        $this->actingAs($this->admin)->get("/penjualan/$punya2")->assertOk();
    }

    public function test_super_admin_tidak_bisa_membuat_transaksi(): void
    {
        $this->actingAs($this->admin)->post('/penjualan', ['items' => [1 => 1]])->assertForbidden();
    }

    public function test_harga_jual_dibekukan_saat_transaksi(): void
    {
        $id = $this->buatBarang(harga: 10000, stok: 20);
        $this->actingAs($this->kasir)->post('/penjualan', ['items' => [$id => 1]]);
        $pid = DB::table('penjualan')->where('iduser', $this->kasir->id)->value('idpenjualan');

        $this->aktifkanMargin(50, $this->admin->id); // margin naik setelah transaksi

        $this->assertSame(12000, (int) DB::table('detail_penjualan')->where('penjualan_idpenjualan', $pid)->value('harga_satuan'));
        $this->assertSame(15000, (int) DB::selectOne('SELECT fn_harga_jual(?) AS h', [$id])->h);
    }

    public function test_laporan_penjualan_per_kasir(): void
    {
        $id = $this->buatBarang(harga: 10000, stok: 20);
        $this->actingAs($this->kasir)->post('/penjualan', ['items' => [$id => 1]]);

        $this->actingAs($this->admin)->get('/laporan-penjualan')->assertOk()
            ->assertSee($this->kasir->name)->assertSee('13.320'); // 12000 + PPN 1320

        $this->actingAs($this->admin)->get('/laporan-penjualan?dari=2026-12-31&sampai=2026-01-01')
            ->assertSessionHasErrors('sampai');
    }
}
