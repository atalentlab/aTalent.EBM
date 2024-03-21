<?php

namespace App\Models;

use App\Enums\AdminNotificationStatus;
use App\Enums\AdminNotificationType;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\NewUserNotification;
use App\Recipients\AdminRecipient;

class AdminNotification extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'content',
        'status',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'admin_notifications';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['log_title'];

    protected $casts = [
        'content' => 'array',
    ];

    public function getLogTitleAttribute(): string
    {
        return $this->title;
    }

    public function getStatus(): string
    {
        return AdminNotificationStatus::getValue($this->status);
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, AdminNotificationStatus::getKeys())) {
            throw new \InvalidArgumentException('Invalid admin notification status ' . $status);
        }

        $this->status = $status;
    }

    public function getStatusColored(): string
    {
        $status = AdminNotificationStatus::getValue($this->status);

        switch ($this->status) {
            case 'to_review':
                return '<span class="text-primary">' . $status . '</span>';
                break;
            case 'accepted':
                return '<span class="text-success">' . $status . '</span>';
                break;
            case 'rejected':
                return '<span class="text-danger">' . $status . '</span>';
                break;
            default:
                return '<span>' . $status . '</span>';
                break;
        }
    }

    public function getType(): string
    {
        return AdminNotificationType::getValue($this->type);
    }

    public function getNewlyRegisteredUser(): ?User
    {
        if (isset($this->content['user_id'])) {
            if ($user = User::find($this->content['user_id'])) {
                return $user;
            }
        }

        return null;
    }

    public function sendNotification()
    {
        switch ($this->type) {
            case 'new_user':
                if ($user = $this->getNewlyRegisteredUser()) {
                    (new AdminRecipient())->notify(new NewUserNotification($user, $this));
                }
                break;
            case 'update_data':

                break;
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($entity)
        {
            // default status
            if (!$entity->status) {
                $entity->status = 'to_review';
            }
        });

        static::created(function($entity)
        {
            $entity->sendNotification();
        });
    }
}
