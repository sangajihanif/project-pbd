<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. View USER (tanpa kolom password) + nama role
        DB::statement("
            CREATE OR REPLACE VIEW view_user AS
            SELECT
                u.id            AS iduser,
                u.name          AS nama_user,
                u.email         AS email,
                u.idrole        AS idrole,
                r.nama_role     AS nama_role,
                u.created_at    AS created_at
            FROM users u
            LEFT JOIN role r ON r.idrole = u.idrole
        ");

        // 2. View ROLE
        DB::statement("
            CREATE OR REPLACE VIEW view_role AS
            SELECT
                idrole,
                nama_role
            FROM role
        ");

        // 3. View SATUAN
        DB::statement("
            CREATE OR REPLACE VIEW view_satuan AS
            SELECT
                idsatuan,
                nama_satuan,
                status,
                CASE WHEN status = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END AS status_text
            FROM satuan
        ");

        // 4. View VENDOR
        DB::statement("
            CREATE OR REPLACE VIEW view_vendor AS
            SELECT
                idvendor,
                nama_vendor,
                badan_hukum,
                CASE WHEN badan_hukum = 'Y' THEN 'Badan Hukum' ELSE 'Non Badan Hukum' END AS badan_hukum_text,
                status,
                CASE WHEN status = 'A' THEN 'Aktif' ELSE 'Tidak Aktif' END AS status_text
            FROM vendor
        ");

        // 5. View BARANG + nama satuan
        DB::statement("
            CREATE OR REPLACE VIEW view_barang AS
            SELECT
                b.idbarang,
                b.jenis,
                b.nama          AS nama_barang,
                b.idsatuan,
                s.nama_satuan,
                b.harga,
                b.status,
                CASE WHEN b.status = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END AS status_text
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
        ");

        // 6. View MARGIN PENJUALAN + nama user pembuat
        DB::statement("
            CREATE OR REPLACE VIEW view_margin_penjualan AS
            SELECT
                m.idmargin_penjualan,
                m.persen,
                m.status,
                CASE WHEN m.status = 1 THEN 'Aktif' ELSE 'Tidak Aktif' END AS status_text,
                m.iduser,
                u.name          AS nama_user,
                m.created_at,
                m.updated_at
            FROM margin_penjualan m
            JOIN users u ON u.id = m.iduser
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_margin_penjualan');
        DB::statement('DROP VIEW IF EXISTS view_barang');
        DB::statement('DROP VIEW IF EXISTS view_vendor');
        DB::statement('DROP VIEW IF EXISTS view_satuan');
        DB::statement('DROP VIEW IF EXISTS view_role');
        DB::statement('DROP VIEW IF EXISTS view_user');
    }
};
