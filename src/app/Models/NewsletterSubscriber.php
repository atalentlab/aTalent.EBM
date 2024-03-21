<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\NewsletterSubscribable;

class NewsletterSubscriber extends Model implements NewsletterSubscribable
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_sent_to_edm_provider',
        'email',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'newsletter_subscriptions';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_sent_to_edm_provider' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return 'Newsletter subscription for ' . $this->email;
    }

    public function getName()
    {
        return null;
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
}
