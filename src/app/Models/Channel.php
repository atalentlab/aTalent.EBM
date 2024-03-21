<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFiles;
use App\Traits\HasTranslations;

class Channel extends Model
{
    use LogsActivity, HasFiles, HasTranslations;

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'published',
        'name',
        'order',
        'logo',
        'organization_url_prefix',
        'organization_url_suffix',
        'ranking_weight',
        'weight_activity',
        'weight_popularity',
        'weight_engagement',
        'post_max_fetch_age',
        'can_collect_views_data',
        'can_collect_likes_data',
        'can_collect_comments_data',
        'can_collect_shares_data',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'channels';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected $appends = ['log_title'];

    protected static $uploadDir = 'channel/logos';

    protected $files = [
        'logo',
    ];

    public function getLogTitleAttribute()
    {
        return $this->name;
    }

    public function organizations()
    {
        return $this->belongsToMany('App\Models\Organization')->withPivot('channel_username');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function organizationData()
    {
        return $this->hasMany('App\Models\OrganizationData');
    }

    public function channelIndices()
    {
        return $this->hasMany('App\Models\ChannelIndex');
    }

    public function crawlerLogs()
    {
        return $this->hasMany('App\Models\CrawlerLog');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($entity) {
            $entity->cleanUpFiles();
        });
    }
}
