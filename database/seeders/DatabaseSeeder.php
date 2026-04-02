<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $pelangganA = Pelanggan::create([
            'NamaPelanggan' => 'Andi Saputra',
            'Alamat' => 'Jl. Melati No.10',
            'NomorTelepon' => '081234567890',
        ]);

        Pelanggan::create([
            'NamaPelanggan' => 'Siti Rahma',
            'Alamat' => 'Jl. Kenanga No.5',
            'NomorTelepon' => '082345678901',
        ]);

        $produk1 = Product::create([
            'NamaProduk' => 'Beras 5kg',
            'Harga' => 75000,
            'Stok' => 30,
        ]);

        $produk2 = Product::create([
            'NamaProduk' => 'Minyak Goreng 1L',
            'Harga' => 18000,
            'Stok' => 50,
        ]);

        Product::create([
            'NamaProduk' => 'Gula 1kg',
            'Harga' => 16000,
            'Stok' => 40,
        ]);

        DB::transaction(function () use ($pelangganA, $produk1, $produk2): void {
            $penjualan = Penjualan::create([
                'TanggalPenjualan' => now()->toDateString(),
                'TotalHarga' => 0,
                'PelangganID' => $pelangganA->PelangganID,
            ]);

            $subtotalA = 1 * (float) $produk1->Harga;
            $subtotalB = 2 * (float) $produk2->Harga;

            DetailPenjualan::create([
                'PenjualanID' => $penjualan->PenjualanID,
                'ProdukID' => $produk1->ProdukID,
                'JumlahProduk' => 1,
                'Subtotal' => $subtotalA,
            ]);

            DetailPenjualan::create([
                'PenjualanID' => $penjualan->PenjualanID,
                'ProdukID' => $produk2->ProdukID,
                'JumlahProduk' => 2,
                'Subtotal' => $subtotalB,
            ]);

            $produk1->decrement('Stok', 1);
            $produk2->decrement('Stok', 2);

            $penjualan->update(['TotalHarga' => $subtotalA + $subtotalB]);
        });
    }
}
