<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // VIEW (tampilan konten modul gudang)


        DB::statement("
            CREATE OR REPLACE VIEW view_pengadaan AS
            SELECT
                p.idpengadaan,
                p.`timestamp`            AS tanggal,
                p.vendor_idvendor        AS idvendor,
                v.nama_vendor,
                p.iduser,
                u.name                   AS nama_user,
                p.status,
                CASE p.status WHEN 'P' THEN 'Menunggu Penerimaan' WHEN 'S' THEN 'Selesai' ELSE p.status END AS status_text,
                p.subtotal_nilai,
                p.ppn,
                p.total_nilai,
                (SELECT COUNT(*) FROM detail_pengadaan d WHERE d.idpengadaan = p.idpengadaan) AS jumlah_item
            FROM pengadaan p
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            JOIN users u  ON u.id = p.iduser
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_detail_pengadaan AS
            SELECT
                d.iddetail_pengadaan,
                d.idpengadaan,
                d.idbarang,
                b.nama                   AS nama_barang,
                s.nama_satuan,
                d.harga_satuan,
                d.jumlah,
                d.sub_total,
                (d.jumlah - fn_sisa_pengadaan(d.idpengadaan, d.idbarang)) AS diterima,
                fn_sisa_pengadaan(d.idpengadaan, d.idbarang)              AS sisa
            FROM detail_pengadaan d
            JOIN barang b ON b.idbarang = d.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_penerimaan AS
            SELECT
                r.idpenerimaan,
                r.created_at             AS tanggal,
                r.idpengadaan,
                v.nama_vendor,
                r.iduser,
                u.name                   AS nama_user,
                (SELECT COUNT(*) FROM detail_penerimaan d WHERE d.idpenerimaan = r.idpenerimaan) AS jumlah_item,
                (SELECT IFNULL(SUM(d.sub_total_terima), 0) FROM detail_penerimaan d WHERE d.idpenerimaan = r.idpenerimaan) AS total_nilai
            FROM penerimaan r
            JOIN pengadaan p ON p.idpengadaan = r.idpengadaan
            JOIN vendor v    ON v.idvendor = p.vendor_idvendor
            JOIN users u     ON u.id = r.iduser
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_detail_penerimaan AS
            SELECT
                d.iddetail_penerimaan,
                d.idpenerimaan,
                d.barang_idbarang        AS idbarang,
                b.nama                   AS nama_barang,
                s.nama_satuan,
                d.jumlah_terima,
                d.harga_satuan_terima,
                d.sub_total_terima,
                fn_sisa_retur(d.iddetail_penerimaan) AS sisa_retur
            FROM detail_penerimaan d
            JOIN barang b ON b.idbarang = d.barang_idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_retur AS
            SELECT
                t.idretur,
                t.created_at             AS tanggal,
                t.idpenerimaan,
                v.nama_vendor,
                t.iduser,
                u.name                   AS nama_user,
                (SELECT IFNULL(SUM(d.jumlah), 0) FROM detail_retur d WHERE d.idretur = t.idretur) AS total_barang
            FROM retur t
            JOIN penerimaan r ON r.idpenerimaan = t.idpenerimaan
            JOIN pengadaan p  ON p.idpengadaan = r.idpengadaan
            JOIN vendor v     ON v.idvendor = p.vendor_idvendor
            JOIN users u      ON u.id = t.iduser
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_detail_retur AS
            SELECT
                d.iddetail_retur,
                d.idretur,
                b.nama                   AS nama_barang,
                s.nama_satuan,
                d.jumlah,
                d.alasan
            FROM detail_retur d
            JOIN detail_penerimaan dp ON dp.iddetail_penerimaan = d.iddetail_penerimaan
            JOIN barang b ON b.idbarang = dp.barang_idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
