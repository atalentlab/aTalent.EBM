<?php

namespace App\Models;

use App\Enums\CrawlerLogStatus;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CrawlerLog extends Model
{
    use LogsActivity;

    protected $table = 'crawler_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'period_id',
        'channel_id',
        'api_user_id',
        'status',
        'posts_crawled_count',
        'is_organization_data_sent',
        'message',
        'crawler_ip',
        'crawled_count',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'organizations';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_organization_data_sent' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return 'Crawler log for ' . $this->organization->log_title . ' during ' . $this->period->log_title . ' on channel ' . $this->channel->log_title;
    }

    public function getStatus()
    {
        return CrawlerLogStatus::getValue($this->status);
    }

    public function getMessage(int $limit = 80)
    {
        return Str::limit($this->message, $limit);
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function period()
    {
        return $this->belongsTo('App\Models\Period');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    public function apiUser()
    {
        return $this->belongsTo('App\Models\ApiUser');
    }
}
