<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class PostData extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id',
        'period_id',
        'like_count',
        'comment_count',
        'share_count',
        'view_count',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'posts';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return 'Post data for "' . $this->post->log_title . '" during ' . $this->period->log_title;
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function period()
    {
        return $this->belongsTo('App\Models\Period');
    }
}
