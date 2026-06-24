<?php

namespace App\Services;

use App\Models\LoyaltyPoint;
use App\Models\PointEarningConfiguration;
use App\Models\RedemptionItem;
use App\Models\RedemptionTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    /**
     * Get atau create loyalty point record untuk user
     */
    public static function getOrCreateLoyaltyPoint(User $user): LoyaltyPoint
    {
        return $user->loyaltyPoint ?? $user->loyaltyPoint()->create([
            'current_points' => 0,
            'total_earned_points' => 0,
            'total_redeemed_points' => 0,
        ]);
    }

    /**
     * Add points ke user berdasarkan nominal pembelian
     */
    public static function addPointsFromPurchase(User $user, float $purchaseAmount): int
    {
        $loyaltyPoint = self::getOrCreateLoyaltyPoint($user);
        $pointsEarned = PointEarningConfiguration::calculatePoints($purchaseAmount);

        if ($pointsEarned > 0) {
            $loyaltyPoint->addPoints($pointsEarned);
        }

        return $pointsEarned;
    }

    /**
     * Process redemption request
     */
    public static function createRedemptionRequest(User $user, RedemptionItem $item, int $quantity = 1, array $shippingData = []): RedemptionTransaction
    {
        return DB::transaction(function () use ($user, $item, $quantity, $shippingData) {
            $loyaltyPoint = self::getOrCreateLoyaltyPoint($user);
            $totalPointsNeeded = $item->points_cost * $quantity;

            // Validasi
            if (!$loyaltyPoint->hasEnoughPoints($totalPointsNeeded)) {
                throw new \Exception('Poin tidak cukup');
            }

            if (!$item->isAvailable()) {
                throw new \Exception('Item tidak tersedia');
            }

            // Check max redemption
            $userRedemptionCount = $item->getUserRedemptionCount($user->id);
            if ($userRedemptionCount + $quantity > $item->max_redemption_per_user) {
                throw new \Exception('Sudah mencapai batas penukaran');
            }

            // Deduct points
            $loyaltyPoint->deductPoints($totalPointsNeeded);

            // Create transaction
            return $user->redemptionTransactions()->create(array_merge([
                'redemption_item_id' => $item->id,
                'points_spent' => $totalPointsNeeded,
                'quantity' => $quantity,
                'status' => 'pending',
            ], $shippingData));
        });
    }

    /**
     * Complete redemption (called by admin)
     */
    public static function completeRedemption(RedemptionTransaction $transaction): bool
    {
        return DB::transaction(function () use ($transaction) {
            if ($transaction->status !== 'pending') {
                return false;
            }

            // Decrement stock
            if (!$transaction->item->decrementStock($transaction->quantity)) {
                throw new \Exception('Stok tidak cukup');
            }

            $transaction->complete();
            return true;
        });
    }

    /**
     * Cancel redemption (return points to user)
     */
    public static function cancelRedemption(RedemptionTransaction $transaction, string $reason = ''): bool
    {
        return DB::transaction(function () use ($transaction, $reason) {
            if ($transaction->status !== 'pending') {
                return false;
            }

            // Return points to user
            $loyaltyPoint = $transaction->user->loyaltyPoint;
            if ($loyaltyPoint) {
                $loyaltyPoint->addPoints($transaction->points_spent);
            }

            $transaction->update(['notes' => $reason]);
            $transaction->cancel();
            return true;
        });
    }

    /**
     * Get user loyalty stats
     */
    public static function getUserStats(User $user): array
    {
        $loyaltyPoint = self::getOrCreateLoyaltyPoint($user);
        $pendingCount = $user->redemptionTransactions()
            ->where('status', 'pending')
            ->count();
        $completedCount = $user->redemptionTransactions()
            ->where('status', 'completed')
            ->count();

        return [
            'current_points' => $loyaltyPoint->current_points,
            'total_earned' => $loyaltyPoint->total_earned_points,
            'total_redeemed' => $loyaltyPoint->total_redeemed_points,
            'pending_transactions' => $pendingCount,
            'completed_transactions' => $completedCount,
        ];
    }

    /**
     * Get admin loyalty stats
     */
    public static function getAdminStats(): array
    {
        $totalUsers = User::count();
        $usersWithPoints = LoyaltyPoint::where('current_points', '>', 0)->count();
        $totalPointsDistributed = LoyaltyPoint::sum('total_earned_points');
        $totalPointsRedeemed = LoyaltyPoint::sum('total_redeemed_points');
        $pendingRedemptions = RedemptionTransaction::where('status', 'pending')->count();
        $completedRedemptions = RedemptionTransaction::where('status', 'completed')->count();

        return [
            'total_users' => $totalUsers,
            'users_with_points' => $usersWithPoints,
            'total_points_distributed' => $totalPointsDistributed,
            'total_points_redeemed' => $totalPointsRedeemed,
            'pending_redemptions' => $pendingRedemptions,
            'completed_redemptions' => $completedRedemptions,
        ];
    }

    /**
     * Get redemption item availability
     */
    public static function getAvailableItems(int $limit = null)
    {
        $query = RedemptionItem::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest();

        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query->get();
    }
}
