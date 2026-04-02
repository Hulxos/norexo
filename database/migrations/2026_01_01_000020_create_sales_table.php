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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('PenjualanID');
            $table->date('TanggalPenjualan');
            $table->decimal('TotalHarga', 10, 2)->default(0);
            $table->unsignedBigInteger('PelangganID')->nullable();
            $table->timestamps();

            $table->foreign('PelangganID')
                ->references('PelangganID')
                ->on('pelanggan')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->index('TanggalPenjualan');
            $table->index('PelangganID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
