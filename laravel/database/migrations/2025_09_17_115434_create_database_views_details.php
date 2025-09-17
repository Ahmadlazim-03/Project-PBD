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
        // View Detail Pengadaan - menampilkan detail pengadaan dengan nama barang
        DB::statement("
            CREATE VIEW view_detail_pengadaan AS
            SELECT 
                dp.iddetail_pengadaan,
                dp.harga_satuan,
                dp.jumlah,
                dp.sub_total,
                dp.idpengadaan,
                dp.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                p.timestamp as tanggal_pengadaan,
                v.nama_vendor
            FROM detail_pengadaan dp
            LEFT JOIN barang b ON dp.idbarang = b.idbarang
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN pengadaan p ON dp.idpengadaan = p.idpengadaan
            LEFT JOIN vendor v ON p.vendor_idvendor = v.idvendor
        ");

        // View Detail Penerimaan - menampilkan detail penerimaan dengan nama barang
        DB::statement("
            CREATE VIEW view_detail_penerimaan AS
            SELECT 
                dpen.iddetail_penerimaan,
                dpen.idpenerimaan,
                dpen.idbarang,
                dpen.jumlah_terima,
                dpen.harga_satuan_terima,
                dpen.sub_total_terima,
                b.nama as nama_barang,
                s.nama_satuan,
                pen.created_at as tanggal_penerimaan,
                u.name as nama_user,
                p.vendor_idvendor,
                v.nama_vendor
            FROM detail_penerimaan dpen
            LEFT JOIN barang b ON dpen.idbarang = b.idbarang
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN penerimaan pen ON dpen.idpenerimaan = pen.idpenerimaan
            LEFT JOIN users u ON pen.iduser = u.id
            LEFT JOIN pengadaan p ON pen.idpengadaan = p.idpengadaan
            LEFT JOIN vendor v ON p.vendor_idvendor = v.idvendor
        ");

        // View Detail Penjualan - menampilkan detail penjualan dengan nama barang
        DB::statement("
            CREATE VIEW view_detail_penjualan AS
            SELECT 
                dpenj.iddetail_penjualan,
                dpenj.harga_satuan,
                dpenj.jumlah,
                dpenj.subtotal,
                dpenj.penjualan_idpenjualan,
                dpenj.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                penj.created_at as tanggal_penjualan,
                u.name as nama_user,
                mp.persen as margin_persen
            FROM detail_penjualan dpenj
            LEFT JOIN barang b ON dpenj.idbarang = b.idbarang
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN penjualan penj ON dpenj.penjualan_idpenjualan = penj.idpenjualan
            LEFT JOIN users u ON penj.iduser = u.id
            LEFT JOIN margin_penjualan mp ON penj.idmargin_penjualan = mp.idmargin_penjualan
        ");

        // View Detail Retur - menampilkan detail retur dengan nama barang
        DB::statement("
            CREATE VIEW view_detail_retur AS
            SELECT 
                dr.iddetail_retur,
                dr.jumlah,
                dr.alasan,
                dr.idretur,
                dr.iddetail_penerimaan,
                dpen.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                r.created_at as tanggal_retur,
                u.name as nama_user,
                dpen.jumlah_terima,
                dpen.harga_satuan_terima
            FROM detail_retur dr
            LEFT JOIN detail_penerimaan dpen ON dr.iddetail_penerimaan = dpen.iddetail_penerimaan
            LEFT JOIN barang b ON dpen.idbarang = b.idbarang
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
            LEFT JOIN retur r ON dr.idretur = r.idretur
            LEFT JOIN users u ON r.iduser = u.id
        ");

        // View Kartu Stok - menampilkan kartu stok dengan nama barang
        DB::statement("
            CREATE VIEW view_kartu_stok AS
            SELECT 
                ks.idkartu_stok,
                ks.jenis_transaksi,
                ks.masuk,
                ks.keluar,
                ks.stok,
                ks.created_at,
                ks.idtransaksi,
                ks.idbarang,
                b.nama as nama_barang,
                s.nama_satuan,
                CASE 
                    WHEN ks.jenis_transaksi = 'M' THEN 'Masuk (Penerimaan)'
                    WHEN ks.jenis_transaksi = 'K' THEN 'Keluar (Penjualan)'
                    WHEN ks.jenis_transaksi = 'R' THEN 'Retur'
                    WHEN ks.jenis_transaksi = 'J' THEN 'Penjualan'
                    ELSE 'Unknown'
                END as jenis_transaksi_text
            FROM kartu_stok ks
            LEFT JOIN barang b ON ks.idbarang = b.idbarang
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
            ORDER BY ks.created_at DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_detail_pengadaan");
        DB::statement("DROP VIEW IF EXISTS view_detail_penerimaan");
        DB::statement("DROP VIEW IF EXISTS view_detail_penjualan");
        DB::statement("DROP VIEW IF EXISTS view_detail_retur");
        DB::statement("DROP VIEW IF EXISTS view_kartu_stok");
    }
};
