<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $primaryKey = 'PenjualanID';

    protected $fillable = [
        'TanggalPenjualan',
        'TotalHarga',
        'PelangganID',
        'IsReturned',
        'ReturnedAt',
    ];

    protected $casts = [
        'TanggalPenjualan' => 'date',
        'TotalHarga' => 'decimal:2',
        'IsReturned' => 'boolean',
        'ReturnedAt' => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'PelangganID', 'PelangganID');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'PenjualanID', 'PenjualanID');
    }
}
