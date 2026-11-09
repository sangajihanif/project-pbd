<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // VIEW SUPER ADMIN  (lengkap: harga dasar, margin, semua kasir)
        // ============================================================

        DB::statement("
            CREATE OR REPLACE VIEW view_sa_barang_stok AS
            SELECT
                b.idbarang,
                b.jenis,
                b.nama                   AS nama_barang,
                s.nama_satuan,
                b.harga                  AS harga_dasar,
                fn_harga_jual(b.idbarang) AS harga_jual,
                fn_stok_barang(b.idbarang) AS stok,
                CASE WHEN b.status = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END AS status_text
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_sa_penjualan AS
            SELECT
                p.idpenjualan,
                p.created_at,
                u.name                   AS nama_kasir,
                m.persen                 AS margin_persen,
                p.subtotal_nilai,
                p.ppn,
                p.total_nilai,
                (SELECT COUNT(*) FROM detail_penjualan d
                  WHERE d.penjualan_idpenjualan = p.idpenjualan) AS jumlah_item
            FROM penjualan p
            JOIN users u            ON u.id = p.iduser
            JOIN margin_penjualan m ON m.idmargin_penjualan = p.idmargin_penjualan
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_sa_kartu_stok AS
            SELECT
                k.idkartu_stok,
                k.created_at,
                b.nama                   AS nama_barang,
                CASE k.jenis_transaksi
                    WHEN 'I' THEN 'Stok Awal'
                    WHEN 'T' THEN 'Penerimaan'
                    WHEN 'J' THEN 'Penjualan'
                    ELSE k.jenis_transaksi
                END                      AS jenis_text,
                k.masuk,
                k.keluar,
                k.stock,
                k.idtransaksi
            FROM kartu_stok k
            JOIN barang b ON b.idbarang = k.idbarang
        ");

        // ============================================================
        // VIEW KASIR  (ringkas: hanya yang dibutuhkan untuk berjualan)
        //  - tanpa harga dasar, margin, dan status
        //  - barang hanya yang aktif
        // ============================================================

        DB::statement("
            CREATE OR REPLACE VIEW view_kasir_barang AS
            SELECT
                b.idbarang,
                b.nama                    AS nama_barang,
                s.nama_satuan,
                fn_harga_jual(b.idbarang) AS harga_jual,
                fn_stok_barang(b.idbarang) AS stok
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
            WHERE b.status = 1
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_kasir_penjualan AS
            SELECT
                p.idpenjualan,
                p.iduser,
                p.created_at,
                p.subtotal_nilai,
                p.ppn,
                p.total_nilai,
                (SELECT COUNT(*) FROM detail_penjualan d
                  WHERE d.penjualan_idpenjualan = p.idpenjualan) AS jumlah_item
            FROM penjualan p
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_kasir_detail_penjualan AS
            SELECT
                d.iddetail_penjualan,
                d.penjualan_idpenjualan AS idpenjualan,
                p.iduser,
                b.nama                  AS nama_barang,
                s.nama_satuan,
                d.harga_satuan,
                d.jumlah,
                d.subtotal
            FROM detail_penjualan d
            JOIN penjualan p ON p.idpenjualan = d.penjualan_idpenjualan
            JOIN barang b    ON b.idbarang    = d.idbarang
            JOIN satuan s    ON s.idsatuan    = b.idsatuan
        ");
    }

    public function down(): void
    {
        foreach ([
            'view_kasir_detail_penjualan', 'view_kasir_penjualan', 'view_kasir_barang',
            'view_sa_kartu_stok', 'view_sa_penjualan', 'view_sa_barang_stok',
        ] as $v) {
            DB::statement("DROP VIEW IF EXISTS {$v}");
        }
    }
};
