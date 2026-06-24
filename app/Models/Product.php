<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'stock',
        'image',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * URL publik untuk gambar produk (storage/app/public → /storage/...).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }
}
