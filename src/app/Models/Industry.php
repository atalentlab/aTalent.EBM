<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Industry extends Model
{
    use LogsActivity , HasTranslations;

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
    ];

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'industries';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected $appends = ['log_title'];

    public function getLogTitleAttribute()
    {
        return $this->name;
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
