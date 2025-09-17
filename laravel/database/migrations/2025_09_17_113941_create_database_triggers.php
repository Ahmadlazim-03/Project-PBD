<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trigger untuk auto update subtotal di detail_pengadaan
        DB::statement("
            CREATE TRIGGER tr_detail_pengadaan_subtotal
            BEFORE INSERT ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                SET NEW.sub_total = NEW.harga_satuan * NEW.jumlah;
            END
        ");

        // Trigger untuk auto update subtotal saat update detail_pengadaan
        DB::statement("
            CREATE TRIGGER tr_detail_pengadaan_update_subtotal
            BEFORE UPDATE ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                SET NEW.sub_total = NEW.harga_satuan * NEW.jumlah;
            END
        ");

        // Trigger untuk auto update total di pengadaan setelah insert detail
        DB::statement("
            CREATE TRIGGER tr_pengadaan_total_after_insert_detail
            AFTER INSERT ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                
                SELECT SUM(sub_total) INTO v_subtotal
                FROM detail_pengadaan 
                WHERE idpengadaan = NEW.idpengadaan;
                
                SET v_ppn = v_subtotal * 0.11;
                
                UPDATE pengadaan SET
                    subtotal_nilai = v_subtotal,
                    ppn = v_ppn,
                    total_nilai = v_subtotal + v_ppn
                WHERE idpengadaan = NEW.idpengadaan;
            END
        ");

        // Trigger untuk auto update total di pengadaan setelah update detail
        DB::statement("
            CREATE TRIGGER tr_pengadaan_total_after_update_detail
            AFTER UPDATE ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                
                SELECT SUM(sub_total) INTO v_subtotal
                FROM detail_pengadaan 
                WHERE idpengadaan = NEW.idpengadaan;
                
                SET v_ppn = v_subtotal * 0.11;
                
                UPDATE pengadaan SET
                    subtotal_nilai = v_subtotal,
                    ppn = v_ppn,
                    total_nilai = v_subtotal + v_ppn
                WHERE idpengadaan = NEW.idpengadaan;
            END
        ");

        // Trigger untuk auto update total di pengadaan setelah delete detail
        DB::statement("
            CREATE TRIGGER tr_pengadaan_total_after_delete_detail
            AFTER DELETE ON detail_pengadaan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                
                SELECT COALESCE(SUM(sub_total), 0) INTO v_subtotal
                FROM detail_pengadaan 
                WHERE idpengadaan = OLD.idpengadaan;
                
                SET v_ppn = v_subtotal * 0.11;
                
                UPDATE pengadaan SET
                    subtotal_nilai = v_subtotal,
                    ppn = v_ppn,
                    total_nilai = v_subtotal + v_ppn
                WHERE idpengadaan = OLD.idpengadaan;
            END
        ");

        // Trigger untuk auto update subtotal di detail_penerimaan
        DB::statement("
            CREATE TRIGGER tr_detail_penerimaan_subtotal
            BEFORE INSERT ON detail_penerimaan
            FOR EACH ROW
            BEGIN
                SET NEW.sub_total_terima = NEW.harga_satuan_terima * NEW.jumlah_terima;
            END
        ");

        // Trigger untuk auto update subtotal saat update detail_penerimaan
        DB::statement("
            CREATE TRIGGER tr_detail_penerimaan_update_subtotal
            BEFORE UPDATE ON detail_penerimaan
            FOR EACH ROW
            BEGIN
                SET NEW.sub_total_terima = NEW.harga_satuan_terima * NEW.jumlah_terima;
            END
        ");

        // Trigger untuk auto update subtotal di detail_penjualan
        DB::statement("
            CREATE TRIGGER tr_detail_penjualan_subtotal
            BEFORE INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                SET NEW.subtotal = NEW.harga_satuan * NEW.jumlah;
            END
        ");

        // Trigger untuk auto update subtotal saat update detail_penjualan
        DB::statement("
            CREATE TRIGGER tr_detail_penjualan_update_subtotal
            BEFORE UPDATE ON detail_penjualan
            FOR EACH ROW
            BEGIN
                SET NEW.subtotal = NEW.harga_satuan * NEW.jumlah;
            END
        ");

        // Trigger untuk auto update total di penjualan setelah insert detail
        DB::statement("
            CREATE TRIGGER tr_penjualan_total_after_insert_detail
            AFTER INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                
                SELECT SUM(subtotal) INTO v_subtotal
                FROM detail_penjualan 
                WHERE penjualan_idpenjualan = NEW.penjualan_idpenjualan;
                
                SET v_ppn = v_subtotal * 0.11;
                
                UPDATE penjualan SET
                    subtotal_nilai = v_subtotal,
                    ppn = v_ppn,
                    total_nilai = v_subtotal + v_ppn
                WHERE idpenjualan = NEW.penjualan_idpenjualan;
            END
        ");

        // Trigger untuk auto update total di penjualan setelah update detail
        DB::statement("
            CREATE TRIGGER tr_penjualan_total_after_update_detail
            AFTER UPDATE ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                
                SELECT SUM(subtotal) INTO v_subtotal
                FROM detail_penjualan 
                WHERE penjualan_idpenjualan = NEW.penjualan_idpenjualan;
                
                SET v_ppn = v_subtotal * 0.11;
                
                UPDATE penjualan SET
                    subtotal_nilai = v_subtotal,
                    ppn = v_ppn,
                    total_nilai = v_subtotal + v_ppn
                WHERE idpenjualan = NEW.penjualan_idpenjualan;
            END
        ");

        // Trigger untuk auto update total di penjualan setelah delete detail
        DB::statement("
            CREATE TRIGGER tr_penjualan_total_after_delete_detail
            AFTER DELETE ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE v_subtotal INT;
                DECLARE v_ppn INT;
                
                SELECT COALESCE(SUM(subtotal), 0) INTO v_subtotal
                FROM detail_penjualan 
                WHERE penjualan_idpenjualan = OLD.penjualan_idpenjualan;
                
                SET v_ppn = v_subtotal * 0.11;
                
                UPDATE penjualan SET
                    subtotal_nilai = v_subtotal,
                    ppn = v_ppn,
                    total_nilai = v_subtotal + v_ppn
                WHERE idpenjualan = OLD.penjualan_idpenjualan;
            END
        ");

        // Trigger untuk update kartu stok saat ada penjualan
        DB::statement("
            CREATE TRIGGER tr_kartu_stok_penjualan
            AFTER INSERT ON detail_penjualan
            FOR EACH ROW
            BEGIN
                DECLARE v_stok_current INT;
                
                -- Ambil stok terkini
                SELECT COALESCE(SUM(masuk - keluar), 0) INTO v_stok_current
                FROM kartu_stok 
                WHERE idbarang = NEW.idbarang;
                
                -- Insert ke kartu stok
                INSERT INTO kartu_stok (
                    jenis_transaksi, 
                    masuk, 
                    keluar, 
                    stok, 
                    created_at, 
                    idtransaksi, 
                    idbarang
                ) VALUES (
                    'J', 
                    0, 
                    NEW.jumlah, 
                    v_stok_current - NEW.jumlah, 
                    NOW(), 
                    NEW.penjualan_idpenjualan, 
                    NEW.idbarang
                );
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS tr_detail_pengadaan_subtotal");
        DB::statement("DROP TRIGGER IF EXISTS tr_detail_pengadaan_update_subtotal");
        DB::statement("DROP TRIGGER IF EXISTS tr_pengadaan_total_after_insert_detail");
        DB::statement("DROP TRIGGER IF EXISTS tr_pengadaan_total_after_update_detail");
        DB::statement("DROP TRIGGER IF EXISTS tr_pengadaan_total_after_delete_detail");
        DB::statement("DROP TRIGGER IF EXISTS tr_detail_penerimaan_subtotal");
        DB::statement("DROP TRIGGER IF EXISTS tr_detail_penerimaan_update_subtotal");
        DB::statement("DROP TRIGGER IF EXISTS tr_detail_penjualan_subtotal");
        DB::statement("DROP TRIGGER IF EXISTS tr_detail_penjualan_update_subtotal");
        DB::statement("DROP TRIGGER IF EXISTS tr_penjualan_total_after_insert_detail");
        DB::statement("DROP TRIGGER IF EXISTS tr_penjualan_total_after_update_detail");
        DB::statement("DROP TRIGGER IF EXISTS tr_penjualan_total_after_delete_detail");
        DB::statement("DROP TRIGGER IF EXISTS tr_kartu_stok_penjualan");
    }
};
