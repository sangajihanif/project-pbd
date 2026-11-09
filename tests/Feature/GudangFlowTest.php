<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\SiapkanData;
use Tests\TestCase;

class GudangFlowTest extends TestCase
{
    use DatabaseTransactions, SiapkanData;

    private $gudang;
    private int $vendor;
    private int $a;
    private int $b;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gudang = $this->gudang();
        $this->vendor = $this->buatVendor();
        $this->a = $this->buatBarang(harga: 5000, stok: 0);
        $this->b = $this->buatBarang(harga: 2000, stok: 0);
        $this->actingAs($this->gudang);
    }

    /** Buat pengadaan: A 10 @6000, B 5 @2500, kembalikan id-nya. */
    private function buatPengadaan(): int
    {
        $this->post('/pengadaan', [
            'idvendor' => $this->vendor,
            'items' => [
                $this->a => ['jumlah' => 10, 'harga' => 6000],
                $this->b => ['jumlah' => 5,  'harga' => 2500],
            ],
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        return (int) DB::table('pengadaan')->where('vendor_idvendor', $this->vendor)->value('idpengadaan');
    }

    public function test_pengadaan_menghitung_total_otomatis(): void
    {
        $id = $this->buatPengadaan();
        $p = DB::table('pengadaan')->where('idpengadaan', $id)->first();

        $this->assertSame('P', $p->status);
        $this->assertSame(72500, (int) $p->subtotal_nilai);  // 60000 + 12500
        $this->assertSame(7975, (int) $p->ppn);              // 11%
        $this->assertSame(80475, (int) $p->total_nilai);

        $this->get("/pengadaan/$id")->assertOk()->assertSee('80.475')->assertSee('Terima Barang');
    }

    public function test_alur_penerimaan_sebagian_lalu_lengkap(): void
    {
        $id = $this->buatPengadaan();

        // terima sebagian: A 6
        $this->post("/penerimaan/$id", ['items' => [$this->a => 6]])->assertSessionHas('success');
        $this->assertSame(6, $this->stok($this->a));
        $this->assertSame('P', DB::table('pengadaan')->where('idpengadaan', $id)->value('status')); // belum selesai
        $this->assertDatabaseHas('kartu_stok', ['idbarang' => $this->a, 'jenis_transaksi' => 'T', 'masuk' => 6]);

        // melebihi sisa ditolak (sisa A = 4)
        $this->post("/penerimaan/$id", ['items' => [$this->a => 5]])->assertSessionHas('error');
        $this->assertSame(6, $this->stok($this->a));

        // terima sisa: A 4 + B 5 -> pengadaan selesai
        $this->post("/penerimaan/$id", ['items' => [$this->a => 4, $this->b => 5]])->assertSessionHas('success');
        $this->assertSame(10, $this->stok($this->a));
        $this->assertSame(5, $this->stok($this->b));
        $this->assertSame('S', DB::table('pengadaan')->where('idpengadaan', $id)->value('status'));

        // sudah selesai: tidak bisa diterima lagi
        $this->get("/penerimaan/create/$id")->assertRedirect("/pengadaan/$id")->assertSessionHas('error');
        $this->post("/penerimaan/$id", ['items' => [$this->a => 1]])->assertSessionHas('error');

        // nilai penerimaan memakai harga pesanan (6000), bukan harga dasar barang
        $this->assertSame(24000, (int) DB::table('detail_penerimaan')
            ->where('barang_idbarang', $this->a)->where('jumlah_terima', 4)->value('sub_total_terima'));
    }

    public function test_barang_di_luar_pengadaan_ditolak(): void
    {
        $id = $this->buatPengadaan();
        $lain = $this->buatBarang(stok: 0);

        $this->post("/penerimaan/$id", ['items' => [$lain => 1]])->assertSessionHas('error');
        $this->assertSame(0, DB::table('penerimaan')->where('idpengadaan', $id)->count());
    }

    public function test_pengadaan_vendor_atau_barang_nonaktif_ditolak(): void
    {
        $nonaktif = $this->buatVendor('N');
        $this->post('/pengadaan', ['idvendor' => $nonaktif, 'items' => [$this->a => ['jumlah' => 1, 'harga' => 100]]])
            ->assertSessionHas('error');

        $mati = $this->buatBarang(stok: 0, status: 0);
        $this->post('/pengadaan', ['idvendor' => $this->vendor, 'items' => [$mati => ['jumlah' => 1, 'harga' => 100]]])
            ->assertSessionHas('error');

        $this->post('/pengadaan', ['idvendor' => $this->vendor, 'items' => [$this->a => ['jumlah' => 0]]])
            ->assertSessionHas('error');

        $this->assertSame(0, DB::table('pengadaan')->where('vendor_idvendor', $this->vendor)->count());
    }

    public function test_alur_retur_mengurangi_stok_dan_validasi(): void
    {
        $id = $this->buatPengadaan();
        $this->post("/penerimaan/$id", ['items' => [$this->a => 10, $this->b => 5]]);
        $pen = (int) DB::table('penerimaan')->where('idpengadaan', $id)->value('idpenerimaan');
        $dA  = (int) DB::table('detail_penerimaan')->where('idpenerimaan', $pen)->where('barang_idbarang', $this->a)->value('iddetail_penerimaan');

        $this->get("/retur/create/$pen")->assertOk()->assertSee('Jumlah Retur');

        // alasan wajib
        $this->post("/retur/$pen", ['items' => [$dA => ['jumlah' => 2, 'alasan' => '']]])->assertSessionHas('error');
        // melebihi jumlah diterima
        $this->post("/retur/$pen", ['items' => [$dA => ['jumlah' => 11, 'alasan' => 'rusak']]])->assertSessionHas('error');
        $this->assertSame(10, $this->stok($this->a));

        // retur valid
        $this->post("/retur/$pen", ['items' => [$dA => ['jumlah' => 3, 'alasan' => 'Kemasan rusak']]])
            ->assertSessionHas('success');
        $this->assertSame(7, $this->stok($this->a));
        $this->assertDatabaseHas('kartu_stok', ['idbarang' => $this->a, 'jenis_transaksi' => 'R', 'keluar' => 3, 'stock' => 7]);

        // sisa retur A tinggal 7 -> 8 ditolak
        $this->post("/retur/$pen", ['items' => [$dA => ['jumlah' => 8, 'alasan' => 'x']]])->assertSessionHas('error');

        $idretur = (int) DB::table('retur')->where('idpenerimaan', $pen)->value('idretur');
        $this->get("/retur/$idretur")->assertOk()->assertSee('Kemasan rusak');
        $this->get('/retur')->assertOk()->assertSee("#$idretur");
    }

    public function test_retur_ditolak_jika_stok_sudah_terjual(): void
    {
        $id = $this->buatPengadaan();
        $this->post("/penerimaan/$id", ['items' => [$this->a => 10]]);
        $pen = (int) DB::table('penerimaan')->where('idpengadaan', $id)->value('idpenerimaan');
        $dA  = (int) DB::table('detail_penerimaan')->where('idpenerimaan', $pen)->value('iddetail_penerimaan');

        // 9 dari 10 sudah terjual
        DB::table('kartu_stok')->insert([
            'jenis_transaksi' => 'J', 'masuk' => 0, 'keluar' => 9, 'stock' => 1, 'idtransaksi' => 0, 'idbarang' => $this->a,
        ]);

        $this->post("/retur/$pen", ['items' => [$dA => ['jumlah' => 5, 'alasan' => 'rusak']]])
            ->assertSessionHas('error');
        $this->assertSame(1, $this->stok($this->a));
    }

    public function test_hapus_pengadaan_hanya_jika_belum_ada_penerimaan(): void
    {
        $bebas = $this->buatPengadaan();
        $this->delete("/pengadaan/$bebas")->assertRedirect('/pengadaan')->assertSessionHas('success');
        $this->assertDatabaseMissing('pengadaan', ['idpengadaan' => $bebas]);
        $this->assertSame(0, DB::table('detail_pengadaan')->where('idpengadaan', $bebas)->count());

        $terpakai = $this->buatPengadaan();
        $this->post("/penerimaan/$terpakai", ['items' => [$this->a => 1]]);
        $this->delete("/pengadaan/$terpakai")->assertSessionHas('error');
        $this->assertDatabaseHas('pengadaan', ['idpengadaan' => $terpakai]);
    }

    public function test_halaman_daftar_modul_gudang(): void
    {
        $id = $this->buatPengadaan();
        $this->post("/penerimaan/$id", ['items' => [$this->a => 2]]);
        $pen = (int) DB::table('penerimaan')->where('idpengadaan', $id)->value('idpenerimaan');

        $this->get('/pengadaan')->assertOk()->assertSee("#$id");
        $this->get('/penerimaan')->assertOk()->assertSee("#$pen");
        $this->get("/penerimaan/$pen")->assertOk()->assertSee('Buat Retur');
        $this->get("/penerimaan/create/$id")->assertOk()->assertSee('Simpan Penerimaan');
        $this->get('/stok')->assertOk(); // Admin Gudang boleh melihat stok
    }

    public function test_kasir_tidak_bisa_memakai_modul_gudang(): void
    {
        $this->actingAs($this->kasir());
        $this->post('/pengadaan', ['idvendor' => $this->vendor, 'items' => [$this->a => ['jumlah' => 1, 'harga' => 1]]])->assertForbidden();
        $this->post('/penerimaan/1', ['items' => [1 => 1]])->assertForbidden();
        $this->post('/retur/1', ['items' => []])->assertForbidden();
    }
}
