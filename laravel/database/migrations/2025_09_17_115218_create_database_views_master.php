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
        // View Barang - menampilkan barang dengan nama satuan
        DB::statement("
            CREATE VIEW view_barang AS
            SELECT 
                b.idbarang,
                b.jenis,
                b.nama,
                b.status,
                b.harga,
                b.idsatuan,
                s.nama_satuan
            FROM barang b
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
        ");

        // View Vendor - menampilkan data vendor
        DB::statement("
            CREATE VIEW view_vendor AS
            SELECT 
                idvendor,
                nama_vendor,
                badan_hukum,
                status,
                CASE 
                    WHEN badan_hukum = 'Y' THEN 'Ya' 
                    ELSE 'Tidak' 
                END as badan_hukum_text,
                CASE 
                    WHEN status = '1' THEN 'Aktif' 
                    ELSE 'Tidak Aktif' 
                END as status_text
            FROM vendor
        ");

        // View Satuan - menampilkan data satuan
        DB::statement("
            CREATE VIEW view_satuan AS
            SELECT 
                idsatuan,
                nama_satuan,
                status,
                CASE 
                    WHEN status = 1 THEN 'Aktif' 
                    ELSE 'Tidak Aktif' 
                END as status_text
            FROM satuan
        ");

        // View Role - menampilkan data role
        DB::statement("
            CREATE VIEW view_role AS
            SELECT 
                idrole,
                nama_role
            FROM role
        ");

        // View User - menampilkan user dengan nama role
        DB::statement("
            CREATE VIEW view_user AS
            SELECT 
                u.id,
                u.name,
                u.email,
                u.email_verified_at,
                u.role_id,
                u.created_at,
                u.updated_at,
                r.nama_role
            FROM users u
            LEFT JOIN role r ON u.role_id = r.idrole
        ");

        // View Margin Penjualan - menampilkan margin dengan nama user
        DB::statement("
            CREATE VIEW view_margin_penjualan AS
            SELECT 
                mp.idmargin_penjualan,
                mp.created_at,
                mp.persen,
                mp.status,
                mp.iduser,
                mp.updated_at,
                u.name as nama_user,
                CASE 
                    WHEN mp.status = 1 THEN 'Aktif' 
                    ELSE 'Tidak Aktif' 
                END as status_text
            FROM margin_penjualan mp
            LEFT JOIN users u ON mp.iduser = u.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_barang");
        DB::statement("DROP VIEW IF EXISTS view_vendor");
        DB::statement("DROP VIEW IF EXISTS view_satuan");
        DB::statement("DROP VIEW IF EXISTS view_role");
        DB::statement("DROP VIEW IF EXISTS view_user");
        DB::statement("DROP VIEW IF EXISTS view_margin_penjualan");
    }
};
