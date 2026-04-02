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
        Schema::create('detailpenjualan', function (Blueprint $table) {
            $table->id('DetailID');
            $table->unsignedBigInteger('PenjualanID');
            $table->unsignedBigInteger('ProdukID');
            $table->integer('JumlahProduk');
            $table->decimal('Subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('PenjualanID')
                ->references('PenjualanID')
                ->on('penjualan')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('ProdukID')
                ->references('ProdukID')
                ->on('produk')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->index('PenjualanID');
            $table->index('ProdukID');
            $table->unique(['PenjualanID', 'ProdukID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailpenjualan');
    }
};
