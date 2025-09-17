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
        // View Pengadaan - menampilkan pengadaan dengan nama user dan vendor
        DB::statement("
            CREATE VIEW view_pengadaan AS
            SELECT 
                p.idpengadaan,
                p.timestamp,
                p.user_iduser,
                p.status,
                p.vendor_idvendor,
                p.subtotal_nilai,
                p.ppn,
                p.total_nilai,
                u.name as nama_user,
                v.nama_vendor,
                CASE 
                    WHEN p.status = 'A' THEN 'Approved' 
                    WHEN p.status = 'P' THEN 'Pending'
                    WHEN p.status = 'R' THEN 'Rejected'
                    ELSE 'Unknown'
                END as status_text
            FROM pengadaan p
            LEFT JOIN users u ON p.user_iduser = u.id
            LEFT JOIN vendor v ON p.vendor_idvendor = v.idvendor
        ");

        // View Penerimaan - menampilkan penerimaan dengan nama user dan data pengadaan
        DB::statement("
            CREATE VIEW view_penerimaan AS
            SELECT 
                pen.idpenerimaan,
                pen.created_at,
                pen.status,
                pen.idpengadaan,
                pen.iduser,
                u.name as nama_user,
                p.vendor_idvendor,
                v.nama_vendor,
                CASE 
                    WHEN pen.status = 'C' THEN 'Complete' 
                    WHEN pen.status = 'P' THEN 'Partial'
                    WHEN pen.status = 'A' THEN 'Approved'
                    ELSE 'Unknown'
                END as status_text
            FROM penerimaan pen
            LEFT JOIN users u ON pen.iduser = u.id
            LEFT JOIN pengadaan p ON pen.idpengadaan = p.idpengadaan
            LEFT JOIN vendor v ON p.vendor_idvendor = v.idvendor
        ");

        // View Penjualan - menampilkan penjualan dengan nama user dan margin
        DB::statement("
            CREATE VIEW view_penjualan AS
            SELECT 
                penj.idpenjualan,
                penj.created_at,
                penj.subtotal_nilai,
                penj.ppn,
                penj.total_nilai,
                penj.iduser,
                penj.idmargin_penjualan,
                u.name as nama_user,
                mp.persen as margin_persen
            FROM penjualan penj
            LEFT JOIN users u ON penj.iduser = u.id
            LEFT JOIN margin_penjualan mp ON penj.idmargin_penjualan = mp.idmargin_penjualan
        ");

        // View Retur - menampilkan retur dengan nama user dan data penerimaan
        DB::statement("
            CREATE VIEW view_retur AS
            SELECT 
                r.idretur,
                r.created_at,
                r.idpenerimaan,
                r.iduser,
                u.name as nama_user,
                pen.idpengadaan,
                p.vendor_idvendor,
                v.nama_vendor
            FROM retur r
            LEFT JOIN users u ON r.iduser = u.id
            LEFT JOIN penerimaan pen ON r.idpenerimaan = pen.idpenerimaan
            LEFT JOIN pengadaan p ON pen.idpengadaan = p.idpengadaan
            LEFT JOIN vendor v ON p.vendor_idvendor = v.idvendor
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_pengadaan");
        DB::statement("DROP VIEW IF EXISTS view_penerimaan");
        DB::statement("DROP VIEW IF EXISTS view_penjualan");
        DB::statement("DROP VIEW IF EXISTS view_retur");
    }
};
