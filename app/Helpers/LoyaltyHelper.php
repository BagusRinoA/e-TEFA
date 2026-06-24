<?php

namespace App\Helpers;

use App\Models\User;

class LoyaltyHelper
{
    /**
     * Format points dengan thousand separator
     */
    public static function formatPoints(int $points): string
    {
        return number_format($points, 0, ',', '.');
    }

    /**
     * Get user current points
     */
    public static function getUserPoints(?User $user): int
    {
        if (!$user) {
            return 0;
        }

        $loyaltyPoint = $user->loyaltyPoint;
        return $loyaltyPoint ? $loyaltyPoint->current_points : 0;
    }

    /**
     * Get status badge color
     */
    public static function getStatusBadgeColor(string $status): string
    {
        return match ($status) {
            'pending' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status badge text
     */
    public static function getStatusBadgeText(string $status): string
    {
        return match ($status) {
            'pending' => '⏳ Pending',
            'completed' => '✓ Selesai',
            'cancelled' => '✕ Dibatalkan',
            default => ucfirst($status),
        };
    }

    /**
     * Format currency
     */
    public static function formatCurrency(float $amount): string
    {
        return 'Rp ' . number_format((int)$amount, 0, ',', '.');
    }

    /**
     * Calculate remaining points for purchase
     */
    public static function getRemainingPointsForNext(int $currentPoints, int $pointsNeeded): int
    {
        $remaining = $pointsNeeded - $currentPoints;
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Get status color class for Tailwind
     */
    public static function getStatusColorClass(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
