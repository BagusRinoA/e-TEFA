<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SavedArticle;
use App\Models\Order;
use App\Models\LoyaltyPoint;
use App\Models\RedemptionTransaction;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'bio',
        'profile_photo',
        'role',
        'profile_visibility',
        'email_notifications',
        'forum_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_visibility' => 'boolean',
            'email_notifications' => 'boolean',
            'forum_notifications' => 'boolean',
        ];
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function savedArticles()
    {
        return $this->hasMany(SavedArticle::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function loyaltyPoint()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    public function redemptionTransactions()
    {
        return $this->hasMany(RedemptionTransaction::class);
    }

    /**
     * Get atau create loyalty point user
     */
    public function getLoyaltyPoint()
    {
        return $this->loyaltyPoint() ?? $this->loyaltyPoint()->create([
            'current_points' => 0,
            'total_earned_points' => 0,
            'total_redeemed_points' => 0,
        ]);
    }
}
