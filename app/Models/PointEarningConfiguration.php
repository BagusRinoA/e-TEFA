<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointEarningConfiguration extends Model
{
    use HasFactory;

    protected $table = 'point_earning_configurations';

    protected $fillable = [
        'min_purchase_amount',
        'max_purchase_amount',
        'points_earned',
        'description',
        'is_active',
    ];

    protected $casts = [
        'min_purchase_amount' => 'decimal:2',
        'max_purchase_amount' => 'decimal:2',
        'points_earned' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get applicable configuration untuk purchase amount
     */
    public static function getApplicableConfig($purchaseAmount)
    {
        return self::where('is_active', true)
            ->where('min_purchase_amount', '<=', $purchaseAmount)
            ->where(function ($query) use ($purchaseAmount) {
                $query->whereNull('max_purchase_amount')
                    ->orWhere('max_purchase_amount', '>=', $purchaseAmount);
            })
            ->orderBy('min_purchase_amount', 'desc')
            ->first();
    }

    /**
     * Calculate points berdasarkan purchase amount
     */
    public static function calculatePoints($purchaseAmount)
    {
        $config = self::getApplicableConfig($purchaseAmount);
        return $config ? $config->points_earned : 0;
    }
}
