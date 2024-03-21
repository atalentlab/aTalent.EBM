<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{
    public function getCauser()
    {
        return $this->causer ? $this->causer->name : 'Anonymous user';
    }

    public function getSubject()
    {
        if ($this->subject) {
            $title = $this->subject->log_title;
        } else {
            $parts = explode('\\', $this->subject_type);
            $title = 'Deleted ' . strtolower(end($parts));
        }

        return $title;
    }
}
