<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Gabungan objek database: Penjualan + Modul Gudang (Pengadaan -> Penerimaan -> Retur)
// Alur status pengadaan:  P (menunggu penerimaan)  --semua barang diterima-->  S (selesai)
// Jenis kartu stok:       I stok awal | T penerimaan | J penjualan | R retur ke vendor

return new class extends Migration
{
    public function up(): void
    {
        
        // 1. FUNCTION (7 Function)
        

        // F1. id margin yang sedang dipakai (aktif, paling baru)
        DB::unprepared('DROP FUNCTION IF EXISTS fn_idmargin_aktif');
        DB::unprepared("
            CREATE FUNCTION fn_idmargin_aktif() RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_id INT DEFAULT NULL;

                SELECT idmargin_penjualan INTO v_id
                FROM margin_penjualan
                WHERE status = 1
                ORDER BY idmargin_penjualan DESC
                LIMIT 1;

                RETURN v_id;
            END
        ");

        // F2. harga jual = harga dasar + margin aktif
        DB::unprepared('DROP FUNCTION IF EXISTS fn_harga_jual');
        DB::unprepared("
            CREATE FUNCTION fn_harga_jual(p_idbarang INT) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_harga  INT DEFAULT 0;
                DECLARE v_persen DOUBLE DEFAULT 0;

                SELECT harga INTO v_harga
                FROM barang
                WHERE idbarang = p_idbarang;

                SELECT persen INTO v_persen
                FROM margin_penjualan
                WHERE idmargin_penjualan = fn_idmargin_aktif();

                RETURN ROUND(v_harga + (v_harga * v_persen / 100));
            END
        ");

        // F3. stok terakhir barang (dibaca dari kartu_stok oleh VIEW)
        DB::unprepared('DROP FUNCTION IF EXISTS fn_stok_barang');
        DB::unprepared("
            CREATE FUNCTION fn_stok_barang(p_idbarang INT) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_stok INT DEFAULT 0;

                SELECT stock INTO v_stok
                FROM kartu_stok
                WHERE idbarang = p_idbarang
                ORDER BY idkartu_stok DESC
                LIMIT 1;

                RETURN IFNULL(v_stok, 0);
            END
        ");

        // F4. Khusus dipakai TRIGGER yang menulis stok. Memakai locking read (FOR UPDATE)
        // supaya selalu membaca stok terbaru, bukan snapshot lama transaksi (REPEATABLE READ).
        DB::unprepared('DROP FUNCTION IF EXISTS fn_stok_barang_kunci');
        DB::unprepared("
            CREATE FUNCTION fn_stok_barang_kunci(p_idbarang INT) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_stok INT DEFAULT 0;

                SELECT stock INTO v_stok
                FROM kartu_stok
                WHERE idbarang = p_idbarang
                ORDER BY idkartu_stok DESC
                LIMIT 1
                FOR UPDATE;

                RETURN IFNULL(v_stok, 0);
            END
        ");

        // F5. sisa barang pesanan pada 1 pengadaan (wajib pakai IFNULL pada SUM)
        DB::unprepared('DROP FUNCTION IF EXISTS fn_sisa_pengadaan');
        DB::unprepared("
            CREATE FUNCTION fn_sisa_pengadaan(p_idpengadaan BIGINT, p_idbarang INT) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_pesan  INT DEFAULT 0;
                DECLARE v_terima INT DEFAULT 0;

                SELECT IFNULL(SUM(jumlah), 0) INTO v_pesan
                FROM detail_pengadaan
                WHERE idpengadaan = p_idpengadaan AND idbarang = p_idbarang;

                SELECT IFNULL(SUM(dt.jumlah_terima), 0) INTO v_terima
                FROM detail_penerimaan dt
                JOIN penerimaan p ON p.idpenerimaan = dt.idpenerimaan
                WHERE p.idpengadaan = p_idpengadaan AND dt.barang_idbarang = p_idbarang;

                RETURN v_pesan - v_terima;
            END
        ");

        // F6. total seluruh sisa barang pada 1 pengadaan (wajib pakai IFNULL pada SUM)
        DB::unprepared('DROP FUNCTION IF EXISTS fn_sisa_total_pengadaan');
        DB::unprepared("
            CREATE FUNCTION fn_sisa_total_pengadaan(p_idpengadaan BIGINT) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_pesan  INT DEFAULT 0;
                DECLARE v_terima INT DEFAULT 0;

                SELECT IFNULL(SUM(jumlah), 0) INTO v_pesan
                FROM detail_pengadaan
                WHERE idpengadaan = p_idpengadaan;

                SELECT IFNULL(SUM(dt.jumlah_terima), 0) INTO v_terima
                FROM detail_penerimaan dt
                JOIN penerimaan p ON p.idpenerimaan = dt.idpenerimaan
                WHERE p.idpengadaan = p_idpengadaan;

                RETURN v_pesan - v_terima;
            END
        ");

        // F7. sisa barang yang masih bisa diretur dari 1 baris detail_penerimaan
        DB::unprepared('DROP FUNCTION IF EXISTS fn_sisa_retur');
        DB::unprepared("
            CREATE FUNCTION fn_sisa_retur(p_iddetail_penerimaan BIGINT) RETURNS INT
            READS SQL DATA
            BEGIN
                DECLARE v_terima INT DEFAULT 0;
                DECLARE v_retur  INT DEFAULT 0;

                SELECT IFNULL(jumlah_terima, 0) INTO v_terima
                FROM detail_penerimaan
                WHERE iddetail_penerimaan = p_iddetail_penerimaan;

                SELECT IFNULL(SUM(jumlah), 0) INTO v_retur
                FROM detail_retur
                WHERE iddetail_penerimaan = p_iddetail_penerimaan;

                RETURN v_terima - v_retur;
            END
        ");

        
        // 2. STORED PROCEDURE (11 Stored Procedure)
        

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_buat_penjualan');
        DB::unprepared("
            CREATE PROCEDURE sp_buat_penjualan(IN p_iduser INT, OUT p_idpenjualan INT)
            BEGIN
                DECLARE v_margin INT;

                SET v_margin = fn_idmargin_aktif();

                IF v_margin IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Belum ada margin penjualan yang aktif';
                END IF;

                INSERT INTO penjualan (iduser, idmargin_penjualan)
                VALUES (p_iduser, v_margin);

                SET p_idpenjualan = LAST_INSERT_ID();
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_tambah_item_penjualan');
        DB::unprepared("
            CREATE PROCEDURE sp_tambah_item_penjualan(
                IN p_idpenjualan INT,
                IN p_idbarang INT,
                IN p_jumlah INT
            )
            BEGIN
                DECLARE v_status TINYINT;

                SELECT status INTO v_status
                FROM barang
                WHERE idbarang = p_idbarang;

                IF v_status IS NULL OR v_status <> 1 THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Barang tidak ditemukan atau tidak aktif';
                END IF;

                INSERT INTO detail_penjualan
                    (harga_satuan, jumlah, subtotal, penjualan_idpenjualan, idbarang)
                VALUES
                    (fn_harga_jual(p_idbarang), p_jumlah, 0, p_idpenjualan, p_idbarang);
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_laporan_penjualan');
        DB::unprepared("
            CREATE PROCEDURE sp_laporan_penjualan(IN p_dari DATE, IN p_sampai DATE)
            BEGIN
                SELECT
                    u.name                           AS kasir,
                    COUNT(p.idpenjualan)             AS jumlah_transaksi,
                    IFNULL(SUM(p.subtotal_nilai), 0) AS total_subtotal,
                    IFNULL(SUM(p.ppn), 0)            AS total_ppn,
                    IFNULL(SUM(p.total_nilai), 0)    AS total_penjualan
                FROM penjualan p
                JOIN users u ON u.id = p.iduser
                WHERE DATE(p.created_at) BETWEEN p_dari AND p_sampai
                GROUP BY u.id, u.name
                ORDER BY total_penjualan DESC;
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_aktifkan_margin');
        DB::unprepared("
            CREATE PROCEDURE sp_aktifkan_margin(IN p_idmargin INT)
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM margin_penjualan WHERE idmargin_penjualan = p_idmargin) THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Margin penjualan tidak ditemukan';
                END IF;

                UPDATE margin_penjualan
                SET status = IF(idmargin_penjualan = p_idmargin, 1, 0);
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_buat_pengadaan');
        DB::unprepared("
            CREATE PROCEDURE sp_buat_pengadaan(IN p_iduser INT, IN p_idvendor INT, OUT p_idpengadaan BIGINT)
            BEGIN
                DECLARE v_status CHAR(1);

                SELECT status INTO v_status FROM vendor WHERE idvendor = p_idvendor;

                IF v_status IS NULL OR v_status <> 'A' THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Vendor tidak ditemukan atau tidak aktif';
                END IF;

                INSERT INTO pengadaan (iduser, vendor_idvendor, status)
                VALUES (p_iduser, p_idvendor, 'P');

                SET p_idpengadaan = LAST_INSERT_ID();
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_tambah_item_pengadaan');
        DB::unprepared("
            CREATE PROCEDURE sp_tambah_item_pengadaan(
                IN p_idpengadaan BIGINT,
                IN p_idbarang INT,
                IN p_jumlah INT,
                IN p_harga INT
            )
            BEGIN
                DECLARE v_status_pengadaan CHAR(1);
                DECLARE v_status_barang TINYINT;

                SELECT status INTO v_status_pengadaan FROM pengadaan WHERE idpengadaan = p_idpengadaan;
                IF v_status_pengadaan IS NULL OR v_status_pengadaan <> 'P' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pengadaan tidak ditemukan atau sudah selesai';
                END IF;

                SELECT status INTO v_status_barang FROM barang WHERE idbarang = p_idbarang;
                IF v_status_barang IS NULL OR v_status_barang <> 1 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Barang tidak ditemukan atau tidak aktif';
                END IF;

                INSERT INTO detail_pengadaan (harga_satuan, jumlah, sub_total, idbarang, idpengadaan)
                VALUES (p_harga, p_jumlah, 0, p_idbarang, p_idpengadaan);
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_hapus_pengadaan');
        DB::unprepared("
            CREATE PROCEDURE sp_hapus_pengadaan(IN p_idpengadaan BIGINT)
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pengadaan WHERE idpengadaan = p_idpengadaan) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pengadaan tidak ditemukan';
                END IF;

                IF EXISTS (SELECT 1 FROM penerimaan WHERE idpengadaan = p_idpengadaan) THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Pengadaan sudah memiliki penerimaan, tidak dapat dihapus';
                END IF;

                DELETE FROM detail_pengadaan WHERE idpengadaan = p_idpengadaan;
                DELETE FROM pengadaan WHERE idpengadaan = p_idpengadaan;
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_buat_penerimaan');
        DB::unprepared("
            CREATE PROCEDURE sp_buat_penerimaan(IN p_idpengadaan BIGINT, IN p_iduser INT, OUT p_idpenerimaan BIGINT)
            BEGIN
                DECLARE v_status CHAR(1);

                SELECT status INTO v_status FROM pengadaan WHERE idpengadaan = p_idpengadaan;
                IF v_status IS NULL OR v_status <> 'P' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pengadaan tidak ditemukan atau sudah selesai';
                END IF;

                INSERT INTO penerimaan (idpengadaan, iduser, status)
                VALUES (p_idpengadaan, p_iduser, 'S');

                SET p_idpenerimaan = LAST_INSERT_ID();
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_tambah_item_penerimaan');
        DB::unprepared("
            CREATE PROCEDURE sp_tambah_item_penerimaan(
                IN p_idpenerimaan BIGINT,
                IN p_idbarang INT,
                IN p_jumlah INT
            )
            BEGIN
                DECLARE v_idpengadaan BIGINT;
                DECLARE v_harga INT;

                SELECT idpengadaan INTO v_idpengadaan FROM penerimaan WHERE idpenerimaan = p_idpenerimaan;

                SELECT harga_satuan INTO v_harga
                FROM detail_pengadaan
                WHERE idpengadaan = v_idpengadaan AND idbarang = p_idbarang
                ORDER BY iddetail_pengadaan
                LIMIT 1;

                IF v_harga IS NULL THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Barang tidak ada dalam pengadaan ini';
                END IF;

                INSERT INTO detail_penerimaan
                    (idpenerimaan, barang_idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
                VALUES
                    (p_idpenerimaan, p_idbarang, p_jumlah, v_harga, 0);
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_buat_retur');
        DB::unprepared("
            CREATE PROCEDURE sp_buat_retur(IN p_idpenerimaan BIGINT, IN p_iduser INT, OUT p_idretur BIGINT)
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM penerimaan WHERE idpenerimaan = p_idpenerimaan) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Penerimaan tidak ditemukan';
                END IF;

                INSERT INTO retur (idpenerimaan, iduser) VALUES (p_idpenerimaan, p_iduser);

                SET p_idretur = LAST_INSERT_ID();
            END
        ");

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_tambah_item_retur');
        DB::unprepared("
            CREATE PROCEDURE sp_tambah_item_retur(
                IN p_idretur BIGINT,
                IN p_iddetail_penerimaan BIGINT,
                IN p_jumlah INT,
                IN p_alasan VARCHAR(200)
            )
            BEGIN
                DECLARE v_pen_retur  BIGINT;
                DECLARE v_pen_detail BIGINT;

                SELECT idpenerimaan INTO v_pen_retur FROM retur WHERE idretur = p_idretur;
                SELECT idpenerimaan INTO v_pen_detail FROM detail_penerimaan
                WHERE iddetail_penerimaan = p_iddetail_penerimaan;

                IF v_pen_retur IS NULL OR v_pen_detail IS NULL OR v_pen_retur <> v_pen_detail THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Item bukan bagian dari penerimaan ini';
                END IF;

                IF TRIM(IFNULL(p_alasan, '')) = '' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Alasan retur wajib diisi';
                END IF;

                INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan)
                VALUES (p_jumlah, TRIM(p_alasan), p_idretur, p_iddetail_penerimaan);
            END
        ");

        
        // 3. TRIGGER (8 Trigger: Pengadaan, Penerimaan, Retur, Penjualan)
        

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_pengadaan_bi');
        DB::unprepared("
            CREATE TRIGGER trg_detail_pengadaan_bi
            BEFORE INSERT ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                IF NEW.jumlah <= 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jumlah harus lebih dari 0';
                END IF;

                IF NEW.harga_satuan < 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Harga tidak boleh negatif';
                END IF;

                SET NEW.sub_total = NEW.harga_satuan * NEW.jumlah;
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_pengadaan_ai');
        DB::unprepared("
            CREATE TRIGGER trg_detail_pengadaan_ai
            AFTER INSERT ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;

                SELECT SUM(sub_total) INTO v_subtotal
                FROM detail_pengadaan WHERE idpengadaan = NEW.idpengadaan;

                SET v_ppn = ROUND(v_subtotal * 0.11);

                UPDATE pengadaan
                SET subtotal_nilai = v_subtotal,
                    ppn            = v_ppn,
                    total_nilai    = v_subtotal + v_ppn
                WHERE idpengadaan = NEW.idpengadaan;
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_penerimaan_bi');
        DB::unprepared("
            CREATE TRIGGER trg_detail_penerimaan_bi
            BEFORE INSERT ON detail_penerimaan
            FOR EACH ROW
            BEGIN
                DECLARE v_idpengadaan BIGINT;
                DECLARE v_lock INT;

                SELECT idbarang INTO v_lock FROM barang WHERE idbarang = NEW.barang_idbarang FOR UPDATE;

                SELECT idpengadaan INTO v_idpengadaan
                FROM penerimaan WHERE idpenerimaan = NEW.idpenerimaan;

                IF NEW.jumlah_terima <= 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jumlah diterima harus lebih dari 0';
                END IF;

                IF NEW.jumlah_terima > fn_sisa_pengadaan(v_idpengadaan, NEW.barang_idbarang) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jumlah diterima melebihi sisa pesanan';
                END IF;

                SET NEW.sub_total_terima = NEW.harga_satuan_terima * NEW.jumlah_terima;
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_penerimaan_ai');
        DB::unprepared("
            CREATE TRIGGER trg_detail_penerimaan_ai
            AFTER INSERT ON detail_penerimaan
            FOR EACH ROW
            BEGIN
                DECLARE v_stok INT;
                DECLARE v_idpengadaan BIGINT;

                SET v_stok = fn_stok_barang_kunci(NEW.barang_idbarang);

                INSERT INTO kartu_stok
                    (jenis_transaksi, masuk, keluar, stock, idtransaksi, idbarang)
                VALUES
                    ('T', NEW.jumlah_terima, 0, v_stok + NEW.jumlah_terima,
                     NEW.idpenerimaan, NEW.barang_idbarang);

                SELECT idpengadaan INTO v_idpengadaan
                FROM penerimaan WHERE idpenerimaan = NEW.idpenerimaan;

                IF fn_sisa_total_pengadaan(v_idpengadaan) = 0 THEN
                    UPDATE pengadaan SET status = 'S' WHERE idpengadaan = v_idpengadaan;
                END IF;
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_retur_bi');
        DB::unprepared("
            CREATE TRIGGER trg_detail_retur_bi
            BEFORE INSERT ON detail_retur
            FOR EACH ROW
            BEGIN
                DECLARE v_idbarang INT;
                DECLARE v_lock INT;

                SELECT barang_idbarang INTO v_idbarang
                FROM detail_penerimaan WHERE iddetail_penerimaan = NEW.iddetail_penerimaan;

                SELECT idbarang INTO v_lock FROM barang WHERE idbarang = v_idbarang FOR UPDATE;

                IF NEW.jumlah <= 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jumlah retur harus lebih dari 0';
                END IF;

                IF NEW.jumlah > fn_sisa_retur(NEW.iddetail_penerimaan) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jumlah retur melebihi jumlah yang diterima';
                END IF;

                IF NEW.jumlah > fn_stok_barang_kunci(v_idbarang) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok barang tidak cukup untuk diretur';
                END IF;
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_retur_ai');
        DB::unprepared("
            CREATE TRIGGER trg_detail_retur_ai
            AFTER INSERT ON detail_retur
            FOR EACH ROW
            BEGIN
                DECLARE v_idbarang INT;
                DECLARE v_stok INT;

                SELECT barang_idbarang INTO v_idbarang
                FROM detail_penerimaan WHERE iddetail_penerimaan = NEW.iddetail_penerimaan;

                SET v_stok = fn_stok_barang_kunci(v_idbarang);

                INSERT INTO kartu_stok
                    (jenis_transaksi, masuk, keluar, stock, idtransaksi, idbarang)
                VALUES
                    ('R', 0, NEW.jumlah, v_stok - NEW.jumlah, NEW.idretur, v_idbarang);
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_penjualan_bi');
        DB::unprepared("
            CREATE TRIGGER trg_detail_penjualan_bi
            BEFORE INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE v_lock INT;

                SELECT idbarang INTO v_lock FROM barang WHERE idbarang = NEW.idbarang FOR UPDATE;

                IF NEW.jumlah <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Jumlah harus lebih dari 0';
                END IF;

                IF NEW.jumlah > fn_stok_barang_kunci(NEW.idbarang) THEN
                    SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Stok barang tidak mencukupi';
                END IF;

                SET NEW.subtotal = NEW.harga_satuan * NEW.jumlah;
            END
        ");

        DB::unprepared('DROP TRIGGER IF EXISTS trg_detail_penjualan_ai');
        DB::unprepared("
            CREATE TRIGGER trg_detail_penjualan_ai
            AFTER INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                DECLARE v_stok INT;

                SET v_stok = fn_stok_barang_kunci(NEW.idbarang);

                INSERT INTO kartu_stok
                    (jenis_transaksi, masuk, keluar, stock, idtransaksi, idbarang)
                VALUES
                    ('J', 0, NEW.jumlah, v_stok - NEW.jumlah,
                     NEW.penjualan_idpenjualan, NEW.idbarang);

                SELECT SUM(subtotal) INTO v_subtotal
                FROM detail_penjualan
                WHERE penjualan_idpenjualan = NEW.penjualan_idpenjualan;

                SET v_ppn = ROUND(v_subtotal * 0.11);

                UPDATE penjualan
                SET subtotal_nilai = v_subtotal,
                    ppn            = v_ppn,
                    total_nilai    = v_subtotal + v_ppn
                WHERE idpenjualan = NEW.penjualan_idpenjualan;
            END
        ");
    }

    public function down(): void
    {
        foreach (
            [
                'trg_detail_retur_ai',
                'trg_detail_retur_bi',
                'trg_detail_penerimaan_ai',
                'trg_detail_penerimaan_bi',
                'trg_detail_pengadaan_ai',
                'trg_detail_pengadaan_bi',
                'trg_detail_penjualan_ai',
                'trg_detail_penjualan_bi',
            ] as $t
        ) {
            DB::unprepared("DROP TRIGGER IF EXISTS {$t}");
        }

        foreach (
            [
                'sp_laporan_penjualan',
                'sp_tambah_item_penjualan',
                'sp_buat_penjualan',
                'sp_tambah_item_retur',
                'sp_buat_retur',
                'sp_tambah_item_penerimaan',
                'sp_buat_penerimaan',
                'sp_hapus_pengadaan',
                'sp_tambah_item_pengadaan',
                'sp_buat_pengadaan',
                'sp_aktifkan_margin',
            ] as $p
        ) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$p}");
        }

        foreach (
            [
                'fn_stok_barang_kunci',
                'fn_sisa_retur',
                'fn_sisa_total_pengadaan',
                'fn_sisa_pengadaan',
                'fn_stok_barang',
                'fn_harga_jual',
                'fn_idmargin_aktif',
            ] as $f
        ) {
            DB::unprepared("DROP FUNCTION IF EXISTS {$f}");
        }
    }
};