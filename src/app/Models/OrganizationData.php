<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class OrganizationData extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'period_id',
        'channel_id',
        'follower_count',
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

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return 'Data for ' . $this->organization->log_title . ' during ' . $this->period->log_title . ' on channel ' . $this->channel->log_title;
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
}
