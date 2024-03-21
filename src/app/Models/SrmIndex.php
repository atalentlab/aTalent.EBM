<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SrmIndex extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'period_id',
        'composite',
        'composite_shift',
        'activity',
        'engagement',
        'popularity',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return 'EBM index for "' . $this->organization->log_title . '" during ' . $this->period->log_title;
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function period()
    {
        return $this->belongsTo('App\Models\Period');
    }

    public function channelIndices()
    {
        return $this->hasMany('App\Models\ChannelIndex');
    }
}
