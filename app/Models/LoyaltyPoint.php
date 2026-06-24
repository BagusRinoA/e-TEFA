<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $table = 'loyalty_points';

    protected $fillable = [
        'user_id',
        'current_points',
        'total_earned_points',
        'total_redeemed_points',
    ];

    protected $casts = [
        'current_points' => 'integer',
        'total_earned_points' => 'integer',
        'total_redeemed_points' => 'integer',
    ];

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tambahkan poin ke user
     */
    public function addPoints($points)
    {
        $this->increment('current_points', $points);
        $this->increment('total_earned_points', $points);
        return $this;
    }

    /**
     * Kurangi poin dari user
     */
    public function deductPoints($points)
    {
        if ($this->current_points >= $points) {
            $this->decrement('current_points', $points);
            $this->increment('total_redeemed_points', $points);
            return true;
        }
        return false;
    }

    /**
     * Check apakah user punya cukup poin
     */
    public function hasEnoughPoints($points)
    {
        return $this->current_points >= $points;
    }
}
