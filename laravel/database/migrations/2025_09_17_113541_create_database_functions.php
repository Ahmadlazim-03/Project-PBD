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
        // Function untuk count barang
        DB::statement("
            CREATE FUNCTION count_barang() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM barang;
                RETURN total;
            END
        ");

        // Function untuk count vendor
        DB::statement("
            CREATE FUNCTION count_vendor() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM vendor;
                RETURN total;
            END
        ");

        // Function untuk count user
        DB::statement("
            CREATE FUNCTION count_user() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM users;
                RETURN total;
            END
        ");

        // Function untuk count satuan
        DB::statement("
            CREATE FUNCTION count_satuan() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM satuan;
                RETURN total;
            END
        ");

        // Function untuk count role
        DB::statement("
            CREATE FUNCTION count_role() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM role;
                RETURN total;
            END
        ");

        // Function untuk count pengadaan barang
        DB::statement("
            CREATE FUNCTION count_pengadaan_barang() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM pengadaan;
                RETURN total;
            END
        ");

        // Function untuk count penjualan barang
        DB::statement("
            CREATE FUNCTION count_penjualan_barang() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM penjualan;
                RETURN total;
            END
        ");

        // Function untuk count penerimaan barang
        DB::statement("
            CREATE FUNCTION count_penerimaan_barang() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM penerimaan;
                RETURN total;
            END
        ");

        // Function untuk count retur barang
        DB::statement("
            CREATE FUNCTION count_retur_barang() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM retur;
                RETURN total;
            END
        ");

        // Function untuk count total detail pengadaan
        DB::statement("
            CREATE FUNCTION count_total_detail_pengadaan() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM detail_pengadaan;
                RETURN total;
            END
        ");

        // Function untuk count total detail penerimaan
        DB::statement("
            CREATE FUNCTION count_total_detail_penerimaan() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM detail_penerimaan;
                RETURN total;
            END
        ");

        // Function untuk count total detail penjualan
        DB::statement("
            CREATE FUNCTION count_total_detail_penjualan() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM detail_penjualan;
                RETURN total;
            END
        ");

        // Function untuk count total detail retur
        DB::statement("
            CREATE FUNCTION count_total_detail_retur() RETURNS INT
            READS SQL DATA
            DETERMINISTIC
            BEGIN
                DECLARE total INT;
                SELECT COUNT(*) INTO total FROM detail_retur;
                RETURN total;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS count_barang");
        DB::statement("DROP FUNCTION IF EXISTS count_vendor");
        DB::statement("DROP FUNCTION IF EXISTS count_user");
        DB::statement("DROP FUNCTION IF EXISTS count_satuan");
        DB::statement("DROP FUNCTION IF EXISTS count_role");
        DB::statement("DROP FUNCTION IF EXISTS count_pengadaan_barang");
        DB::statement("DROP FUNCTION IF EXISTS count_penjualan_barang");
        DB::statement("DROP FUNCTION IF EXISTS count_penerimaan_barang");
        DB::statement("DROP FUNCTION IF EXISTS count_retur_barang");
        DB::statement("DROP FUNCTION IF EXISTS count_total_detail_pengadaan");
        DB::statement("DROP FUNCTION IF EXISTS count_total_detail_penerimaan");
        DB::statement("DROP FUNCTION IF EXISTS count_total_detail_penjualan");
        DB::statement("DROP FUNCTION IF EXISTS count_total_detail_retur");
    }
};
