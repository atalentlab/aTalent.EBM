<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFiles;
use App\Traits\HasTranslations;

class Organization extends Model
{
    use LogsActivity, HasFiles, HasTranslations;

    const MAX_COMPETITORS = 5;

    public $translatable = ['name'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'published',
        'is_fetching',
        'name',
        'logo',
        'industry_id',
        'intro',
        'website',
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
        'published' => 'boolean',
        'is_fetching' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public static $uploadDir = 'organization/logos';

    protected $files = [
        'logo',
    ];

    public function getLogTitleAttribute()
    {
        return $this->name;
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function channels()
    {
        return $this->belongsToMany('App\Models\Channel')->withPivot('channel_username');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function organizationData()
    {
        return $this->hasMany('App\Models\OrganizationData');
    }

    public function srmIndices()
    {
        return $this->hasMany('App\Models\SrmIndex');
    }

    public function channelIndices()
    {
        return $this->hasMany('App\Models\ChannelIndex');
    }

    public function crawlerLogs()
    {
        return $this->hasMany('App\Models\CrawlerLog');
    }

    public function organizations()
    {
        return $this->belongsToMany('App\Models\Organization', 'organization_competitors', 'organization_id', 'competitor_id');
    }

    public function competitors()
    {
        return $this->belongsToMany('App\Models\Organization', 'organization_competitors', 'competitor_id', 'organization_id');
    }

    public function syncCompetitors(array $competitorIds = [], bool $detaching = true)
    {
        // don't add own ID to competitors
        if (($key = array_search($this->id, $competitorIds)) !== false) {
            unset($competitorIds[$key]);
        }

        // Max 5 competitors
        if ($detaching) {
            if (count($competitorIds) > self::MAX_COMPETITORS) return false;
        }
        else {
            if (count(array_unique(array_merge($competitorIds, $this->competitors->pluck('id')->toArray()))) > self::MAX_COMPETITORS) return false;
        }

        return $this->competitors()->sync($competitorIds, $detaching);
    }

    public function isProfileInComplete()
    {
        return $this->industry_id == null || $this->channels->count() == 0;
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($entity) {
            $entity->cleanUpFiles();
        });
    }
}
