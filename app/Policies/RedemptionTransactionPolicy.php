<?php

namespace App\Policies;

use App\Models\RedemptionTransaction;
use App\Models\User;

class RedemptionTransactionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RedemptionTransaction $transaction): bool
    {
        return $user->id === $transaction->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RedemptionTransaction $transaction): bool
    {
        // Only admin can update
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RedemptionTransaction $transaction): bool
    {
        // Only admin can delete
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve/complete the model.
     */
    public function approve(User $user, RedemptionTransaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, RedemptionTransaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can cancel their own transaction.
     */
    public function cancel(User $user, RedemptionTransaction $transaction): bool
    {
        return $user->id === $transaction->user_id && $transaction->isPending();
    }
}
