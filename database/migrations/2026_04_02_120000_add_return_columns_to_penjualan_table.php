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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->boolean('IsReturned')->default(false)->after('PelangganID');
            $table->timestamp('ReturnedAt')->nullable()->after('IsReturned');
            $table->index('IsReturned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropIndex(['IsReturned']);
            $table->dropColumn(['IsReturned', 'ReturnedAt']);
        });
    }
};
