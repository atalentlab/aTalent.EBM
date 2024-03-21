<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelIndex extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'srm_index_id',
        'channel_id',
        'organization_id',
        'period_id',
        'composite',
        'composite_shift',
        'activity',
        'engagement',
        'popularity',
        'follower_count',
        'post_count',
        'like_count',
        'comment_count',
        'share_count',
        'view_count',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute(): string
    {
        if ($this->channel && $this->organization && $this->period) {
            return $this->channel->log_title . ' index for "' . $this->organization->log_title . '" during ' . $this->period->log_title;
        }

        return '';
    }

    public function srmIndex()
    {
        return $this->belongsTo('App\Models\SrmIndex');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function period()
    {
        return $this->belongsTo('App\Models\Period');
    }
}
