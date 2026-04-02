<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'ProdukID';

    protected $fillable = [
        'NamaProduk',
        'Harga',
        'Stok',
        'image_path',
    ];

    protected $casts = [
        'Harga' => 'decimal:2',
    ];

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'ProdukID', 'ProdukID');
    }
}
