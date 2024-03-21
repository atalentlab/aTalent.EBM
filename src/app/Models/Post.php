<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id',
        'organization_id',
        'post_id',
        'title',
        'is_actively_fetching',
        'posted_date',
        'url',
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'posts';

    protected $dates = [
        'posted_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_actively_fetching' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return Str::limit($this->title, 70) ?? 'untitled ' . $this->channel->log_title . ' post';
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    public function postData()
    {
        return $this->hasMany('App\Models\PostData');
    }

    public function getPostUrl(): ?string
    {
        if ($this->url) {
            return $this->url;
        }

        switch ($this->channel_id) {
            case 1: // LinkedIn
                return 'https://www.linkedin.com/feed/update/urn:li:activity:' . $this->post_id . '/';
                break;
            case 2: // WeChat
                return null;
                break;
            case 3: // Weibo
                return 'https://m.weibo.cn/status/' . $this->post_id;
                break;
            case 4: // Kanzhun
                if (preg_match('/^[0-9]+$/', $this->post_id)) {
                    // oldest posts that have been taken offline by kanzhun
                    return null;
                }
                elseif (preg_match('/\.html/', $this->post_id)) {
                    // post URL for old post ID's
                    return 'https://m.kanzhun.com' . $this->post_id;
                }
                else {
                    // post URL for new encrypted post ID's
                    return 'https://www.kanzhun.com/firm/interview/detail/' . $this->post_id . '.html';
                }
                break;
            default:
                return null;
                break;
        }
    }
}
