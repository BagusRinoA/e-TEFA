<?php

namespace App\Models;

use App\Models\RedemptionItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedemptionTransaction extends Model
{
    use HasFactory;

    protected $table = 'redemption_transactions';

    protected $fillable = [
        'user_id',
        'redemption_item_id',
        'points_spent',
        'quantity',
        'status',
        'notes',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'redeemed_at',
    ];

    protected $casts = [
        'points_spent' => 'integer',
        'quantity' => 'integer',
        'redeemed_at' => 'datetime',
    ];

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship ke RedemptionItem
     */
    public function item()
    {
        return $this->belongsTo(RedemptionItem::class, 'redemption_item_id');
    }

    /**
     * Mark transaction sebagai completed
     */
    public function complete(): self
    {
        $this->status = 'completed';
        $this->redeemed_at = now();
        $this->save();
        return $this;
    }

    /**
     * Mark transaction sebagai cancelled
     */
    public function cancel(): self
    {
        $this->status = 'cancelled';
        $this->save();
        return $this;
    }

    /**
     * Check apakah transaction dalam status pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check apakah transaction telah completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
