<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(){
        // Menggunakan database functions yang telah dibuat
        $totalBarang = DB::select('SELECT count_barang() AS total')[0]->total;
        $totalVendor = DB::select('SELECT count_vendor() AS total')[0]->total;
        $totalUser = DB::select('SELECT count_user() AS total')[0]->total;
        $totalSatuan = DB::select('SELECT count_satuan() AS total')[0]->total;
        $totalRole = DB::select('SELECT count_role() AS total')[0]->total;
    
        $totalPengadaan = DB::select('SELECT count_pengadaan_barang() AS total')[0]->total;
        $totalPenjualan = DB::select('SELECT count_penjualan_barang() AS total')[0]->total;
        $totalPenerimaan = DB::select('SELECT count_penerimaan_barang() AS total')[0]->total;
        $totalRetur = DB::select('SELECT count_retur_barang() AS total')[0]->total;
    
        $totalDetailPengadaan = DB::select('SELECT count_total_detail_pengadaan() AS total')[0]->total;
        $totalDetailPenerimaan = DB::select('SELECT count_total_detail_penerimaan() AS total')[0]->total;
        $totalDetailPenjualan = DB::select('SELECT count_total_detail_penjualan() AS total')[0]->total;
        $totalDetailRetur = DB::select('SELECT count_total_detail_retur() AS total')[0]->total;
    
        $data = [
            'labels' => ['Detail Pengadaan Barang', 'Detail Penerimaan Barang', 'Detail Penjualan Barang', 'Detail Retur Barang'],
            'data' => [$totalDetailPengadaan, $totalDetailPenerimaan, $totalDetailPenjualan, $totalDetailRetur] 
        ];
    
        $data1 = [
            'labels' => ['Barang', 'Vendor', 'User', 'Satuan', 'Role'],
            'data' => [$totalBarang, $totalVendor, $totalUser, $totalSatuan, $totalRole] 
        ];

      
    
        return view('Dashboard.dashboard', [
            'totalPengadaan' => $totalPengadaan,
            'totalPenjualan' => $totalPenjualan,
            'totalPenerimaan' => $totalPenerimaan,
            'totalRetur' => $totalRetur,
            'totalDetailPengadaan' => $totalDetailPengadaan, 
            'totalDetailPenerimaan' => $totalDetailPenerimaan, 
            'totalDetailPenjualan' => $totalDetailPenjualan, 
            'totalDetailRetur' => $totalDetailRetur,
            'totalBarang' => $totalBarang,
            'totalVendor' => $totalVendor, 
            'totalUser' => $totalUser, 
            'totalSatuan' => $totalSatuan, 
            'totalRole' => $totalRole,
            'data' => $data,
            'data1' => $data1
        ]);
    }    
}
