<?php

namespace App\Models;

use App\Services\UserVerificationService;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Auth\ResetPasswordNotification;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use App\Contracts\NewsletterSubscribable;
use App\Traits\HasMemberships;

class User extends Authenticatable implements NewsletterSubscribable
{
    use Notifiable, LogsActivity, CausesActivity, HasRoles, HasMemberships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activated',
        'verified',
        'last_login',
        'organization_id',
        'phone',
        'additional_data',
        'is_sent_to_edm_provider',
        'receives_my_organization_report',
        'receives_competitor_report',
        'agreed_to_toc',
    ];

    protected static $logAttributes = [
        'name',
        'email',
        'activated',
        'verified',
        'phone',
        'additional_data',
        'is_sent_to_edm_provider',
        'receives_my_organization_report',
        'receives_competitor_report',
        'agreed_to_toc',
    ];

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'last_login',
        'password',
        'remember_token',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'last_login',
    ];

    protected $casts = [
        'activated' => 'boolean',
        'verified' => 'boolean',
        'is_sent_to_edm_provider' => 'boolean',
        'additional_data' => 'array',
        'receives_my_organization_report' => 'boolean',
        'receives_competitor_report' => 'boolean',
        'agreed_to_toc' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getLogTitleAttribute()
    {
        return $this->name;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getHomepage(): string
    {
        if ($this->can('view my organization')) {
            return route('admin.my-organization.index');
        }

        if ($this->can('view statistics dashboard')) {
            return route('admin.dashboard.index');
        }

        return route('admin.profile.show');
    }

    public function sendVerificationNotification()
    {
        (new UserVerificationService())->sendVerificationMail($this);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setSubscribed()
    {
        $this->is_sent_to_edm_provider = true;
        $this->save();
    }

    public function canAddCompetitor(Organization $organization): bool
    {
        if (!$this->can( 'manage competitors')) {
            return false;
        }

        if ($this->can( 'manage multiple competitors') && $organization->competitors->count() < 5) {
            return true;
        }

        if (!$this->can( 'manage multiple competitors') && $organization->competitors->count() == 0) {
            return true;
        }

        return false;
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function($entity)
        {
            // clean up password reset token
            DB::table('password_resets')->where('email', $entity->email)->delete();

            // clean up  verification token
            DB::table('user_verifications')->where('user_id', $entity->id)->delete();
        });
    }
}
