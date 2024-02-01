<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UkuranBan extends Model
{
    protected $table = 'ukuran_ban';

    protected $fillable = ['ukuran'];

    // Jika Anda ingin menambahkan relasi dengan produk
    public function products()
{
    return $this->belongsToMany(Product::class, 'product_ukuran_ban', 'ukuran_ban_id', 'product_id')->withTimestamps();
}
}