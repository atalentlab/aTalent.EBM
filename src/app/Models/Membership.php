<?php

namespace App\Models;

use App\Notifications\UserMembershipExpired;
use App\Notifications\UserTrialExpired;
use App\Recipients\AdminRecipient;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class Membership extends Model
{
    const MEMBERSHIP_ROLES = [
        'Premium User',
        'Enterprise User',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id',
        'is_trial',
        'expires_at',
        'source',
        'order',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at',
    ];

    protected $casts = [
        'is_trial' => 'boolean',
    ];

    protected $appends = ['time_left', 'duration'];

    public function getTimeLeftAttribute()
    {
        if (!$this->expires_at || $this->expires_at <= now()) return '';

        if ($diffInDays = $this->expires_at->diffInDays()) {
            return $diffInDays . 'd';
        }

        return $this->expires_at->diffInHours() . 'h';
    }

    public function getTimeLeftPretty()
    {
        $timeLeft = str_replace('d', ' days', $this->time_left);
        $timeLeft = str_replace('h', ' hours', $timeLeft);

        return $timeLeft;

    }

    public function getDurationAttribute()
    {
        if (!$this->expires_at) return '';

        return $this->expires_at->diffInDays($this->created_at) . 'd';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at && $this->expires_at >= now();
    }

    public function scopeActive(Builder $query)
    {
        $query->whereNotNull('expires_at')
            ->where('expires_at', '>=', now());
    }

    public function scopeInactive(Builder $query)
    {
        $query->whereNull('expires_at')
            ->orWhere('expires_at', '<', now());
    }

    public function scopeTrial(Builder $query)
    {
        $query->where('is_trial', 1);
    }

    public function scopeNotTrial(Builder $query)
    {
        $query->where('is_trial', 0);
    }

    public function scopeOrdered(Builder $query)
    {
        $query->orderBy('order');
    }

    public function sendExpiredNotification(): bool
    {
        if (!$this->user) {
            return false;
        }

        $notification = $this->is_trial ? new UserTrialExpired($this->user, $this) : new UserMembershipExpired($this->user, $this);

        (new AdminRecipient($this->user->email))->notify($notification);

        return true;
    }

    public static function membershipRolesList()
    {
        return Role::whereIn('name', self::MEMBERSHIP_ROLES)->get(['name', 'id']);
    }
}
