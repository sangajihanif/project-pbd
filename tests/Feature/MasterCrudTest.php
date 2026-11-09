<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\SiapkanData;
use Tests\TestCase;

class MasterCrudTest extends TestCase
{
    use DatabaseTransactions, SiapkanData;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->superAdmin();
        $this->actingAs($this->admin);
    }

    // ---------------- SATUAN ----------------
    public function test_satuan_input_edit_toggle_hapus(): void
    {
        $this->post('/satuan', ['nama_satuan' => 'Satuan Baru'])->assertRedirect('/satuan');
        $id = DB::table('satuan')->where('nama_satuan', 'Satuan Baru')->value('idsatuan');
        $this->assertNotNull($id);

        $this->post('/satuan', ['nama_satuan' => 'Satuan Baru'])->assertSessionHasErrors('nama_satuan'); // unik

        $this->put("/satuan/$id/update", ['nama_satuan' => 'Satuan Ubah'])->assertRedirect('/satuan');
        $this->assertSame('Satuan Ubah', DB::table('satuan')->where('idsatuan', $id)->value('nama_satuan'));

        $this->patch("/satuan/$id/status")->assertSessionHas('success');
        $this->assertSame(0, (int) DB::table('satuan')->where('idsatuan', $id)->value('status'));

        $this->delete("/satuan/$id")->assertRedirect('/satuan');
        $this->assertDatabaseMissing('satuan', ['idsatuan' => $id]);
    }

    public function test_satuan_yang_dipakai_barang_tidak_bisa_dihapus(): void
    {
        $idbarang = $this->buatBarang();
        $idsatuan = DB::table('barang')->where('idbarang', $idbarang)->value('idsatuan');

        $this->delete("/satuan/$idsatuan")->assertSessionHas('error');
        $this->assertDatabaseHas('satuan', ['idsatuan' => $idsatuan]);
    }

    // ---------------- ROLE ----------------
    public function test_role_input_edit_hapus_dan_proteksi(): void
    {
        $this->post('/role', ['nama_role' => 'Supervisor'])->assertRedirect('/role');
        $id = DB::table('role')->where('nama_role', 'Supervisor')->value('idrole');

        $this->put("/role/$id/update", ['nama_role' => 'Supervisor Toko'])->assertRedirect('/role');
        $this->assertDatabaseHas('role', ['idrole' => $id, 'nama_role' => 'Supervisor Toko']);

        // role bawaan tidak boleh dihapus
        $this->delete('/role/1')->assertSessionHas('error');
        $this->assertDatabaseHas('role', ['idrole' => 1]);

        // role yang dipakai user tidak boleh dihapus
        $user = $this->buatUser($id);
        $this->delete("/role/$id")->assertSessionHas('error');
        $this->assertDatabaseHas('role', ['idrole' => $id]);

        $user->delete();
        $this->delete("/role/$id")->assertRedirect('/role');
        $this->assertDatabaseMissing('role', ['idrole' => $id]);
    }

    public function test_halaman_role_punya_modal_edit(): void
    {
        $this->get('/role')->assertOk()->assertSee('updateData1', false)->assertSee('createData', false);
    }

    // ---------------- VENDOR ----------------
    public function test_vendor_input_edit_toggle_hapus(): void
    {
        $this->post('/vendor', ['nama_vendor' => 'PT Baru Jaya', 'badan_hukum' => 'Y'])->assertSessionHasNoErrors();
        $id = DB::table('vendor')->where('nama_vendor', 'PT Baru Jaya')->value('idvendor');
        $this->assertNotNull($id);

        $this->put("/vendor/$id/update", ['nama_vendor' => 'PT Baru Makmur', 'badan_hukum' => 'N'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('vendor', ['idvendor' => $id, 'nama_vendor' => 'PT Baru Makmur']);

        $this->patch("/vendor/$id/status")->assertSessionHas('success');

        $this->delete("/vendor/$id")->assertRedirect('/vendor');
        $this->assertDatabaseMissing('vendor', ['idvendor' => $id]);
    }

    public function test_vendor_yang_punya_pengadaan_tidak_bisa_dihapus(): void
    {
        $vendor = $this->buatVendor();
        DB::statement('CALL sp_buat_pengadaan(?, ?, @id)', [$this->admin->id, $vendor]);

        $this->delete("/vendor/$vendor")->assertSessionHas('error');
        $this->assertDatabaseHas('vendor', ['idvendor' => $vendor]);
    }

    // ---------------- BARANG ----------------
    public function test_barang_input_edit_hapus(): void
    {
        $idsatuan = DB::table('satuan')->insertGetId(['nama_satuan' => 'Uji Satuan', 'status' => 1]);

        $this->post('/barang', [
            'jenis' => 'M', 'nama' => 'Kopi Uji', 'idsatuan' => $idsatuan, 'harga' => 15000,
        ])->assertSessionHasNoErrors();
        $id = DB::table('barang')->where('nama', 'Kopi Uji')->value('idbarang');
        $this->assertNotNull($id);

        $this->put("/barang/$id/update", [
            'jenis' => 'M', 'nama' => 'Kopi Uji 2', 'idsatuan' => $idsatuan, 'harga' => 17000,
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('barang', ['idbarang' => $id, 'harga' => 17000]);

        $this->delete("/barang/$id")->assertRedirect('/barang');
        $this->assertDatabaseMissing('barang', ['idbarang' => $id]);
    }

    public function test_barang_yang_sudah_bertransaksi_tidak_bisa_dihapus(): void
    {
        $id = $this->buatBarang(); // punya kartu stok

        $this->delete("/barang/$id")->assertSessionHas('error');
        $this->assertDatabaseHas('barang', ['idbarang' => $id]);
    }

    // ---------------- MARGIN ----------------
    public function test_margin_hanya_satu_yang_aktif(): void
    {
        $lama = $this->aktifkanMargin(10, $this->admin->id);

        $this->post('/margin-penjualan', ['persen' => 40])->assertSessionHasNoErrors();
        $baru = DB::table('margin_penjualan')->where('persen', 40)->value('idmargin_penjualan');

        $this->assertSame(1, DB::table('margin_penjualan')->where('status', 1)->count());
        $this->assertSame(1, (int) DB::table('margin_penjualan')->where('idmargin_penjualan', $baru)->value('status'));
        $this->assertSame(0, (int) DB::table('margin_penjualan')->where('idmargin_penjualan', $lama)->value('status'));

        // toggle margin lama -> jadi aktif, yang baru otomatis nonaktif
        $this->patch("/margin-penjualan/$lama/status")->assertSessionHas('success');
        $this->assertSame(1, (int) DB::table('margin_penjualan')->where('idmargin_penjualan', $lama)->value('status'));
        $this->assertSame(0, (int) DB::table('margin_penjualan')->where('idmargin_penjualan', $baru)->value('status'));
        $this->assertSame(1, DB::table('margin_penjualan')->where('status', 1)->count());

        // toggle yang aktif -> nonaktif
        $this->patch("/margin-penjualan/$lama/status");
        $this->assertSame(0, DB::table('margin_penjualan')->where('status', 1)->count());
    }

    public function test_margin_validasi_persen(): void
    {
        $this->post('/margin-penjualan', ['persen' => 150])->assertSessionHasErrors('persen');
        $this->post('/margin-penjualan', ['persen' => -5])->assertSessionHasErrors('persen');
        $this->post('/margin-penjualan', ['persen' => 'abc'])->assertSessionHasErrors('persen');
    }

    public function test_margin_hapus_dan_proteksi_jika_dipakai(): void
    {
        $bebas = $this->aktifkanMargin(5, $this->admin->id);
        $this->delete("/margin-penjualan/$bebas")->assertRedirect('/margin-penjualan');
        $this->assertDatabaseMissing('margin_penjualan', ['idmargin_penjualan' => $bebas]);

        $dipakai = $this->aktifkanMargin(7, $this->admin->id);
        DB::statement('CALL sp_buat_penjualan(?, @id)', [$this->admin->id]);
        $this->delete("/margin-penjualan/$dipakai")->assertSessionHas('error');
        $this->assertDatabaseHas('margin_penjualan', ['idmargin_penjualan' => $dipakai]);
    }

    // ---------------- USER ----------------
    public function test_user_edit_dan_hapus(): void
    {
        $target = $this->tamu();

        $this->put("/user/{$target->id}/update", [
            'name' => 'Nama Baru', 'email' => $target->email, 'idrole' => 3,
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['id' => $target->id, 'idrole' => 3, 'name' => 'Nama Baru']);

        $this->delete("/user/{$target->id}")->assertRedirect('/user');
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_user_tidak_bisa_hapus_diri_sendiri(): void
    {
        $this->delete("/user/{$this->admin->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_non_admin_tidak_bisa_memakai_aksi_master(): void
    {
        $kasir = $this->kasir();
        $this->actingAs($kasir);

        $this->post('/satuan', ['nama_satuan' => 'X'])->assertForbidden();
        $this->delete('/vendor/1')->assertForbidden();
        $this->put("/user/{$kasir->id}/update", ['name' => 'a', 'email' => $kasir->email, 'idrole' => 1])->assertForbidden();
        $this->assertSame(3, (int) DB::table('users')->where('id', $kasir->id)->value('idrole')); // tidak bisa naik role
    }
}
