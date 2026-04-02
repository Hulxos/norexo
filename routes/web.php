<?php

use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KasirController::class, 'index'])->name('kasir.index');
Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('kasir.dashboard');
Route::get('/beranda/chart-data', [KasirController::class, 'chartData'])->name('kasir.chart-data');
Route::get('/produk/kelola', [KasirController::class, 'kelolaProduk'])->name('kasir.produk.index');
Route::post('/produk/kelola', [KasirController::class, 'simpanProduk'])->name('kasir.produk.store');
Route::patch('/produk/kelola/{produk}', [KasirController::class, 'updateProduk'])->name('kasir.produk.update');
Route::post('/keranjang/tambah', [KasirController::class, 'tambahKeranjang'])->name('kasir.keranjang.tambah');
Route::patch('/keranjang/{produk}', [KasirController::class, 'updateKeranjang'])->name('kasir.keranjang.update');
Route::delete('/keranjang/{produk}', [KasirController::class, 'hapusKeranjang'])->name('kasir.keranjang.hapus');
Route::post('/transaksi/simpan', [KasirController::class, 'simpanTransaksi'])->name('kasir.transaksi.simpan');
Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('kasir.riwayat');
Route::get('/faq', [KasirController::class, 'faq'])->name('kasir.faq');
Route::patch('/riwayat/{penjualan}/return', [KasirController::class, 'returnTransaksi'])->name('kasir.riwayat.return');
Route::delete('/riwayat/{penjualan}', [KasirController::class, 'hapusTransaksi'])->name('kasir.riwayat.hapus');
