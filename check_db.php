<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$pelanggan = DB::table('pelanggan')->count();
$produk = DB::table('produk')->count();
$penjualan = DB::table('penjualan')->count();

echo "Pelanggan: $pelanggan\n";
echo "Produk: $produk\n";
echo "Penjualan: $penjualan\n";

if ($pelanggan > 0) {
    echo "\nDaftar Pelanggan:\n";
    $listPelanggan = DB::table('pelanggan')->select('NamaPelanggan', 'NomorTelepon')->get();
    foreach ($listPelanggan as $item) {
        echo "- {$item->NamaPelanggan} ({$item->NomorTelepon})\n";
    }
}

if ($produk > 0) {
    echo "\nDaftar Produk:\n";
    $listProduk = DB::table('produk')->select('NamaProduk', 'Harga', 'Stok')->limit(5)->get();
    foreach ($listProduk as $item) {
        echo "- {$item->NamaProduk} - Rp " . number_format($item->Harga, 0, ',', '.') . " (Stok: {$item->Stok})\n";
    }
}

if ($penjualan > 0) {
    echo "\nTransaksi Terakhir:\n";
    $last = DB::table('penjualan')->latest('PenjualanID')->first();
    echo "- #{$last->PenjualanID} pada {$last->TanggalPenjualan}, total Rp " . number_format($last->TotalHarga, 0, ',', '.');
}
