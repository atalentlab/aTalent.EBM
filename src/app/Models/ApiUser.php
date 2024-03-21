<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ApiUser extends Authenticatable
{
    use LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activated',
        'name',
        'api_token',
        'last_login',
    ];

    protected static $logAttributes = [
        'activated',
        'name',
        'api_token',
    ];

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'last_login',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'api_users';

    protected $dates = [
        'created_at',
        'updated_at',
        'last_login',
    ];

    protected $casts = [
        'activated' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return $this->name;
    }

    public function crawlerLogs()
    {
        return $this->hasMany('App\Models\CrawlerLog');
    }
}
