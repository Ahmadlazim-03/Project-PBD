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
        // Stored procedure untuk insert detail retur
        DB::statement("
            CREATE PROCEDURE insert_detail_retur(
                IN p_jumlah INT,
                IN p_alasan VARCHAR(200),
                IN p_idretur BIGINT,
                IN p_iddetail_penerimaan BIGINT
            )
            BEGIN
                DECLARE v_idbarang INT;
                
                -- Ambil idbarang dari detail penerimaan
                SELECT idbarang INTO v_idbarang 
                FROM detail_penerimaan 
                WHERE iddetail_penerimaan = p_iddetail_penerimaan;
                
                INSERT INTO detail_retur (
                    jumlah, 
                    alasan, 
                    idretur, 
                    iddetail_penerimaan
                ) VALUES (
                    p_jumlah, 
                    p_alasan, 
                    p_idretur, 
                    p_iddetail_penerimaan
                );
                
                -- Update kartu stok (kurangi stok karena retur)
                INSERT INTO kartu_stok (
                    jenis_transaksi, 
                    masuk, 
                    keluar, 
                    stok, 
                    created_at, 
                    idtransaksi, 
                    idbarang
                ) VALUES (
                    'R', 
                    0, 
                    p_jumlah, 
                    (SELECT COALESCE(SUM(masuk - keluar), 0) - p_jumlah FROM kartu_stok WHERE idbarang = v_idbarang), 
                    NOW(), 
                    p_idretur, 
                    v_idbarang
                );
            END
        ");

        // Stored procedure untuk update detail retur
        DB::statement("
            CREATE PROCEDURE update_detail_retur(
                IN p_id INT,
                IN p_jumlah INT,
                IN p_alasan VARCHAR(200),
                IN p_idretur BIGINT,
                IN p_iddetail_penerimaan BIGINT
            )
            BEGIN
                DECLARE old_jumlah INT;
                DECLARE v_idbarang INT;
                
                -- Ambil data lama
                SELECT jumlah INTO old_jumlah FROM detail_retur WHERE iddetail_retur = p_id;
                
                -- Ambil idbarang
                SELECT idbarang INTO v_idbarang 
                FROM detail_penerimaan 
                WHERE iddetail_penerimaan = p_iddetail_penerimaan;
                
                -- Update detail retur
                UPDATE detail_retur SET
                    jumlah = p_jumlah,
                    alasan = p_alasan,
                    idretur = p_idretur,
                    iddetail_penerimaan = p_iddetail_penerimaan
                WHERE iddetail_retur = p_id;
                
                -- Update kartu stok
                UPDATE kartu_stok SET
                    keluar = p_jumlah,
                    stok = stok + old_jumlah - p_jumlah
                WHERE idtransaksi = p_idretur AND idbarang = v_idbarang AND jenis_transaksi = 'R';
            END
        ");

        // Stored procedure untuk delete detail retur
        DB::statement("
            CREATE PROCEDURE delete_detail_retur(
                IN p_id INT
            )
            BEGIN
                DECLARE del_jumlah INT;
                DECLARE del_idretur BIGINT;
                DECLARE v_idbarang INT;
                
                -- Ambil data yang akan dihapus
                SELECT dr.jumlah, dr.idretur, dp.idbarang 
                INTO del_jumlah, del_idretur, v_idbarang
                FROM detail_retur dr
                JOIN detail_penerimaan dp ON dr.iddetail_penerimaan = dp.iddetail_penerimaan
                WHERE dr.iddetail_retur = p_id;
                
                -- Hapus dari kartu stok
                DELETE FROM kartu_stok 
                WHERE idtransaksi = del_idretur AND idbarang = v_idbarang AND jenis_transaksi = 'R';
                
                -- Hapus detail retur
                DELETE FROM detail_retur WHERE iddetail_retur = p_id;
            END
        ");

        // Stored procedures for users
        DB::statement("
            CREATE PROCEDURE insert_user(
                IN p_name VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_role_id INT
            )
            BEGIN
                INSERT INTO users (name, email, password, role_id, created_at, updated_at)
                VALUES (p_name, p_email, p_password, p_role_id, NOW(), NOW());
            END
        ");

        DB::statement("
            CREATE PROCEDURE update_user(
                IN p_id BIGINT,
                IN p_name VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_role_id INT
            )
            BEGIN
                UPDATE users SET
                    name = p_name,
                    email = p_email,
                    password = p_password,
                    role_id = p_role_id,
                    updated_at = NOW()
                WHERE id = p_id;
            END
        ");

        DB::statement("
            CREATE PROCEDURE delete_user(
                IN p_id BIGINT
            )
            BEGIN
                DELETE FROM users WHERE id = p_id;
            END
        ");

        // Stored procedures for vendors
        DB::statement("
            CREATE PROCEDURE insert_vendor(
                IN p_nama_vendor VARCHAR(100),
                IN p_badan_hukum CHAR(1),
                IN p_status CHAR(1)
            )
            BEGIN
                INSERT INTO vendor (nama_vendor, badan_hukum, status)
                VALUES (p_nama_vendor, p_badan_hukum, p_status);
            END
        ");

        DB::statement("
            CREATE PROCEDURE update_vendor(
                IN p_id INT,
                IN p_nama_vendor VARCHAR(100),
                IN p_badan_hukum CHAR(1),
                IN p_status CHAR(1)
            )
            BEGIN
                UPDATE vendor SET
                    nama_vendor = p_nama_vendor,
                    badan_hukum = p_badan_hukum,
                    status = p_status
                WHERE idvendor = p_id;
            END
        ");

        DB::statement("
            CREATE PROCEDURE delete_vendor(
                IN p_id INT
            )
            BEGIN
                DELETE FROM vendor WHERE idvendor = p_id;
            END
        ");

        // Stored procedures for satuan
        DB::statement("
            CREATE PROCEDURE insert_satuan(
                IN p_nama_satuan VARCHAR(45),
                IN p_status TINYINT
            )
            BEGIN
                INSERT INTO satuan (nama_satuan, status)
                VALUES (p_nama_satuan, p_status);
            END
        ");

        DB::statement("
            CREATE PROCEDURE update_satuan(
                IN p_id INT,
                IN p_nama_satuan VARCHAR(45),
                IN p_status TINYINT
            )
            BEGIN
                UPDATE satuan SET
                    nama_satuan = p_nama_satuan,
                    status = p_status
                WHERE idsatuan = p_id;
            END
        ");

        DB::statement("
            CREATE PROCEDURE delete_satuan(
                IN p_id INT
            )
            BEGIN
                DELETE FROM satuan WHERE idsatuan = p_id;
            END
        ");

        // Stored procedures for roles
        DB::statement("
            CREATE PROCEDURE insert_role(
                IN p_nama_role VARCHAR(45)
            )
            BEGIN
                INSERT INTO role (nama_role)
                VALUES (p_nama_role);
            END
        ");

        DB::statement("
            CREATE PROCEDURE update_role(
                IN p_id INT,
                IN p_nama_role VARCHAR(45)
            )
            BEGIN
                UPDATE role SET
                    nama_role = p_nama_role
                WHERE idrole = p_id;
            END
        ");

        DB::statement("
            CREATE PROCEDURE delete_role(
                IN p_id INT
            )
            BEGIN
                DELETE FROM role WHERE idrole = p_id;
            END
        ");

        // Complex stored procedure for retur
        DB::statement("
            CREATE PROCEDURE insert_retur_dengan_detail(
                IN p_idpenerimaan BIGINT,
                IN p_iduser BIGINT,
                IN p_details JSON
            )
            BEGIN
                DECLARE v_idretur BIGINT;
                DECLARE i INT DEFAULT 0;
                DECLARE detail_count INT;
                
                -- Insert retur
                INSERT INTO retur (created_at, idpenerimaan, iduser)
                VALUES (NOW(), p_idpenerimaan, p_iduser);
                
                SET v_idretur = LAST_INSERT_ID();
                
                -- Process details from JSON
                SET detail_count = JSON_LENGTH(p_details);
                
                WHILE i < detail_count DO
                    INSERT INTO detail_retur (
                        jumlah, 
                        alasan, 
                        idretur, 
                        iddetail_penerimaan
                    ) VALUES (
                        JSON_UNQUOTE(JSON_EXTRACT(p_details, CONCAT('$[', i, '].jumlah'))),
                        JSON_UNQUOTE(JSON_EXTRACT(p_details, CONCAT('$[', i, '].alasan'))),
                        v_idretur,
                        JSON_UNQUOTE(JSON_EXTRACT(p_details, CONCAT('$[', i, '].iddetail_penerimaan')))
                    );
                    SET i = i + 1;
                END WHILE;
            END
        ");

        DB::statement("
            CREATE PROCEDURE update_retur(
                IN p_id BIGINT,
                IN p_idpenerimaan BIGINT,
                IN p_iduser BIGINT
            )
            BEGIN
                UPDATE retur SET
                    idpenerimaan = p_idpenerimaan,
                    iduser = p_iduser
                WHERE idretur = p_id;
            END
        ");

        DB::statement("
            CREATE PROCEDURE delete_retur(
                IN p_id BIGINT
            )
            BEGIN
                -- Hapus detail retur terlebih dahulu
                DELETE FROM detail_retur WHERE idretur = p_id;
                
                -- Hapus retur
                DELETE FROM retur WHERE idretur = p_id;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS insert_detail_retur");
        DB::statement("DROP PROCEDURE IF EXISTS update_detail_retur");
        DB::statement("DROP PROCEDURE IF EXISTS delete_detail_retur");
        DB::statement("DROP PROCEDURE IF EXISTS insert_user");
        DB::statement("DROP PROCEDURE IF EXISTS update_user");
        DB::statement("DROP PROCEDURE IF EXISTS delete_user");
        DB::statement("DROP PROCEDURE IF EXISTS insert_vendor");
        DB::statement("DROP PROCEDURE IF EXISTS update_vendor");
        DB::statement("DROP PROCEDURE IF EXISTS delete_vendor");
        DB::statement("DROP PROCEDURE IF EXISTS insert_satuan");
        DB::statement("DROP PROCEDURE IF EXISTS update_satuan");
        DB::statement("DROP PROCEDURE IF EXISTS delete_satuan");
        DB::statement("DROP PROCEDURE IF EXISTS insert_role");
        DB::statement("DROP PROCEDURE IF EXISTS update_role");
        DB::statement("DROP PROCEDURE IF EXISTS delete_role");
        DB::statement("DROP PROCEDURE IF EXISTS insert_retur_dengan_detail");
        DB::statement("DROP PROCEDURE IF EXISTS update_retur");
        DB::statement("DROP PROCEDURE IF EXISTS delete_retur");
    }
};
