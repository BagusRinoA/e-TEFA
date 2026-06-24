<?php

namespace App\Models;

use App\Models\RedemptionTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionItem extends Model
{
    use HasFactory;

    protected $table = 'redemption_items';

    protected $fillable = [
        'name',
        'description',
        'points_cost',
        'stock',
        'image_url',
        'max_redemption_per_user',
        'is_active',
    ];

    protected $casts = [
        'points_cost' => 'integer',
        'stock' => 'integer',
        'max_redemption_per_user' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship ke RedemptionTransaction
     */
    public function transactions()
    {
        return $this->hasMany(RedemptionTransaction::class);
    }

    /**
     * Check apakah item masih tersedia
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * Kurangi stok item
     */
    public function decrementStock(int $quantity = 1): bool
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Tambahkan stok item
     */
    public function incrementStock(int $quantity = 1): self
    {
        $this->increment('stock', $quantity);
        return $this;
    }

    /**
     * Get redemption count untuk user tertentu
     */
    public function getUserRedemptionCount(int $userId): int
    {
        return $this->transactions()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
    }
}
