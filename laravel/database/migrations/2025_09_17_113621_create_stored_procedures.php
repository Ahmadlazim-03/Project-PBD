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
        // Stored procedure untuk insert detail penerimaan
        DB::statement("
            CREATE PROCEDURE insert_detail_penerimaan(
                IN p_idpenerimaan BIGINT,
                IN p_idbarang INT,
                IN p_jumlah_terima INT,
                IN p_harga_satuan_terima INT,
                IN p_sub_total_terima INT
            )
            BEGIN
                INSERT INTO detail_penerimaan (
                    idpenerimaan, 
                    idbarang, 
                    jumlah_terima, 
                    harga_satuan_terima, 
                    sub_total_terima
                ) VALUES (
                    p_idpenerimaan, 
                    p_idbarang, 
                    p_jumlah_terima, 
                    p_harga_satuan_terima, 
                    p_sub_total_terima
                );
                
                -- Update kartu stok (tambah stok masuk)
                INSERT INTO kartu_stok (
                    jenis_transaksi, 
                    masuk, 
                    keluar, 
                    stok, 
                    created_at, 
                    idtransaksi, 
                    idbarang
                ) VALUES (
                    'M', 
                    p_jumlah_terima, 
                    0, 
                    (SELECT COALESCE(SUM(masuk - keluar), 0) + p_jumlah_terima FROM kartu_stok WHERE idbarang = p_idbarang), 
                    NOW(), 
                    p_idpenerimaan, 
                    p_idbarang
                );
            END
        ");

        // Stored procedure untuk update detail penerimaan
        DB::statement("
            CREATE PROCEDURE update_detail_penerimaan(
                IN p_id BIGINT,
                IN p_idpenerimaan BIGINT,
                IN p_idbarang INT,
                IN p_jumlah_terima INT,
                IN p_harga_satuan_terima INT,
                IN p_sub_total_terima INT
            )
            BEGIN
                DECLARE old_jumlah INT;
                DECLARE old_idbarang INT;
                
                -- Ambil data lama
                SELECT jumlah_terima, idbarang INTO old_jumlah, old_idbarang
                FROM detail_penerimaan WHERE iddetail_penerimaan = p_id;
                
                -- Update detail penerimaan
                UPDATE detail_penerimaan SET
                    idpenerimaan = p_idpenerimaan,
                    idbarang = p_idbarang,
                    jumlah_terima = p_jumlah_terima,
                    harga_satuan_terima = p_harga_satuan_terima,
                    sub_total_terima = p_sub_total_terima
                WHERE iddetail_penerimaan = p_id;
                
                -- Update kartu stok (koreksi stok)
                UPDATE kartu_stok SET
                    masuk = p_jumlah_terima,
                    stok = stok - old_jumlah + p_jumlah_terima
                WHERE idtransaksi = p_idpenerimaan AND idbarang = old_idbarang AND jenis_transaksi = 'M';
            END
        ");

        // Stored procedure untuk delete detail penerimaan
        DB::statement("
            CREATE PROCEDURE delete_detail_penerimaan(
                IN p_id BIGINT
            )
            BEGIN
                DECLARE del_jumlah INT;
                DECLARE del_idbarang INT;
                DECLARE del_idpenerimaan BIGINT;
                
                -- Ambil data yang akan dihapus
                SELECT jumlah_terima, idbarang, idpenerimaan INTO del_jumlah, del_idbarang, del_idpenerimaan
                FROM detail_penerimaan WHERE iddetail_penerimaan = p_id;
                
                -- Hapus dari kartu stok
                DELETE FROM kartu_stok 
                WHERE idtransaksi = del_idpenerimaan AND idbarang = del_idbarang AND jenis_transaksi = 'M';
                
                -- Hapus detail penerimaan
                DELETE FROM detail_penerimaan WHERE iddetail_penerimaan = p_id;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS insert_detail_penerimaan");
        DB::statement("DROP PROCEDURE IF EXISTS update_detail_penerimaan");
        DB::statement("DROP PROCEDURE IF EXISTS delete_detail_penerimaan");
    }
};
