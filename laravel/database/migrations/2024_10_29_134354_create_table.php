<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE TABLE role (
            idrole INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nama_role VARCHAR(45) NOT NULL
        );
    ");

    DB::statement("
        CREATE TABLE users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL DEFAULT NULL,
            password VARCHAR(255) NOT NULL,
            role_id INT UNSIGNED NOT NULL,
            remember_token VARCHAR(100) NULL DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL,
            FOREIGN KEY (role_id) REFERENCES role(idrole) ON DELETE CASCADE
        )
    ");

    // Tabel satuan
    DB::statement("
        CREATE TABLE satuan (
            idsatuan INT PRIMARY KEY AUTO_INCREMENT,
            nama_satuan VARCHAR(45) NOT NULL,
            status TINYINT NULL
        );
    ");

    // Tabel vendor
    DB::statement("
        CREATE TABLE vendor (
            idvendor INT PRIMARY KEY AUTO_INCREMENT,
            nama_vendor VARCHAR(100) NOT NULL,
            badan_hukum CHAR(1) NULL,
            status CHAR(1) NULL
        );
    ");




    // Tabel barang
    DB::statement("
        CREATE TABLE barang (
            idbarang INT PRIMARY KEY AUTO_INCREMENT,
            jenis CHAR(1) NULL,
            nama VARCHAR(45) NOT NULL,
            status TINYINT NULL,
            harga INT,
            idsatuan INT,
            FOREIGN KEY (idsatuan) REFERENCES satuan(idsatuan) ON DELETE CASCADE
        );
    ");

    // Tabel pengadaan
    DB::statement("
        CREATE TABLE pengadaan (
            idpengadaan BIGINT PRIMARY KEY AUTO_INCREMENT,
            timestamp TIMESTAMP NULL,
            user_iduser BIGINT UNSIGNED,
            status CHAR(1),
            vendor_idvendor INT,
            subtotal_nilai INT NULL,
            ppn INT NULL,
            total_nilai INT NULL,
            FOREIGN KEY (user_iduser) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (vendor_idvendor) REFERENCES vendor(idvendor) ON DELETE SET NULL
        );
    ");

    // Tabel detail_pengadaan
    DB::statement("
        CREATE TABLE detail_pengadaan (
            iddetail_pengadaan BIGINT PRIMARY KEY AUTO_INCREMENT,
            harga_satuan INT NULL,
            jumlah INT NULL,
            sub_total INT NULL,
            idpengadaan BIGINT,
            idbarang INT,
            FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan) ON DELETE CASCADE,
            FOREIGN KEY (idbarang) REFERENCES barang(idbarang) ON DELETE CASCADE
        );
    ");

    // Tabel penerimaan
    DB::statement("
        CREATE TABLE penerimaan (
            idpenerimaan BIGINT PRIMARY KEY AUTO_INCREMENT,
            created_at TIMESTAMP NULL,
            status CHAR(1) NULL,
            idpengadaan BIGINT,
            iduser BIGINT UNSIGNED,
            FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan) ON DELETE CASCADE,
            FOREIGN KEY (iduser) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // Tabel detail_penerimaan
    DB::statement("
        CREATE TABLE detail_penerimaan (
            iddetail_penerimaan BIGINT PRIMARY KEY AUTO_INCREMENT,
            idpenerimaan BIGINT,
            idbarang INT,
            jumlah_terima INT NULL,
            harga_satuan_terima INT NULL,
            sub_total_terima INT NULL,
            FOREIGN KEY (idpenerimaan) REFERENCES penerimaan(idpenerimaan) ON DELETE CASCADE,
            FOREIGN KEY (idbarang) REFERENCES barang(idbarang) ON DELETE CASCADE
        );
    ");

    // Tabel kartu_stok
    DB::statement("
        CREATE TABLE kartu_stok (
            idkartu_stok BIGINT PRIMARY KEY AUTO_INCREMENT,
            jenis_transaksi CHAR(1) NULL,
            masuk INT NULL,
            keluar INT NULL,
            stok INT NULL,
            created_at TIMESTAMP NULL,
            idtransaksi INT NULL,
            idbarang INT,
            FOREIGN KEY (idbarang) REFERENCES barang(idbarang) ON DELETE CASCADE
        );
    ");

    // Tabel retur
    DB::statement("
        CREATE TABLE retur (
            idretur BIGINT PRIMARY KEY AUTO_INCREMENT,
            created_at TIMESTAMP NULL,
            idpenerimaan BIGINT,
            iduser BIGINT UNSIGNED,
            FOREIGN KEY (idpenerimaan) REFERENCES penerimaan(idpenerimaan) ON DELETE CASCADE,
            FOREIGN KEY (iduser) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // Tabel detail_retur
    DB::statement("
        CREATE TABLE detail_retur (
            iddetail_retur INT PRIMARY KEY AUTO_INCREMENT,
            jumlah INT NULL,
            alasan VARCHAR(200) NULL,
            idretur BIGINT,
            iddetail_penerimaan BIGINT,
            FOREIGN KEY (idretur) REFERENCES retur(idretur) ON DELETE CASCADE,
            FOREIGN KEY (iddetail_penerimaan) REFERENCES detail_penerimaan(iddetail_penerimaan) ON DELETE CASCADE
        );
    ");

    // Tabel margin_penjualan
    DB::statement("
        CREATE TABLE margin_penjualan (
            idmargin_penjualan INT PRIMARY KEY AUTO_INCREMENT,
            created_at TIMESTAMP NULL,
            persen DOUBLE NULL,
            status TINYINT NULL,
            iduser BIGINT UNSIGNED,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (iduser) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // Tabel penjualan
    DB::statement("
        CREATE TABLE penjualan (
            idpenjualan INT PRIMARY KEY AUTO_INCREMENT,
            created_at TIMESTAMP NULL,
            subtotal_nilai INT NULL,
            ppn INT NULL,
            total_nilai INT NULL,
            iduser BIGINT UNSIGNED,
            idmargin_penjualan INT,
            FOREIGN KEY (iduser) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (idmargin_penjualan) REFERENCES margin_penjualan(idmargin_penjualan) ON DELETE SET NULL
        );
    ");

    // Tabel detail_penjualan
    DB::statement("
        CREATE TABLE detail_penjualan (
            iddetail_penjualan BIGINT PRIMARY KEY AUTO_INCREMENT,
            harga_satuan INT NULL,
            jumlah INT NULL,
            subtotal INT NULL,
            penjualan_idpenjualan INT,
            idbarang INT,
            FOREIGN KEY (penjualan_idpenjualan) REFERENCES penjualan(idpenjualan) ON DELETE CASCADE,
            FOREIGN KEY (idbarang) REFERENCES barang(idbarang) ON DELETE CASCADE
        );
    ");

    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CreateTable');
        DB::statement("DROP TABLE IF EXISTS detail_penjualan;");
        DB::statement("DROP TABLE IF EXISTS penjualan;");
        DB::statement("DROP TABLE IF EXISTS margin_penjualan;");
        DB::statement("DROP TABLE IF EXISTS detail_retur;");
        DB::statement("DROP TABLE IF EXISTS retur;");
        DB::statement("DROP TABLE IF EXISTS kartu_stok;");
        DB::statement("DROP TABLE IF EXISTS detail_penerimaan;");
        DB::statement("DROP TABLE IF EXISTS penerimaan;");
        DB::statement("DROP TABLE IF EXISTS detail_pengadaan;");
        DB::statement("DROP TABLE IF EXISTS pengadaan;");
        DB::statement("DROP TABLE IF EXISTS barang;");
        DB::statement("DROP TABLE IF EXISTS sessions;");
        DB::statement("DROP TABLE IF EXISTS users;");
        DB::statement("DROP TABLE IF EXISTS vendor;");
        DB::statement("DROP TABLE IF EXISTS satuan;");
        DB::statement("DROP TABLE IF EXISTS role;");
    }
};
