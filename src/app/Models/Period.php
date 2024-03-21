<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Period extends Model
{
    use LogsActivity, HasTranslations;

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'published',
        'name',
        'start_date',
        'end_date',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'periods';

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected $appends = ['log_title', 'name_with_year'];

    public function getLogTitleAttribute()
    {
        return $this->name_with_year;
    }

    public function getNameWithYearAttribute()
    {
        if ($this->start_date) {
            return $this->start_date->format('Y') . ' - ' . $this->name;
        }

        return $this->name;
    }

    public function postData()
    {
        return $this->hasMany('App\Models\PostData');
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

    public function getPreviousPeriod()
    {
        return Period::whereDate('end_date', '<', $this->start_date)->orderBy('end_date', 'desc')->first();
    }
}
