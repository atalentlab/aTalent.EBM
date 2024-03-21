<?php

namespace App\Enums;

class AdminNotificationStatus extends Enum
{
    protected static $content = [
        'to_review'     => 'To review',
        'in_progress'   => 'In progress',
        'accepted'      => 'Accepted',
        'rejected'      => 'Rejected',
    ];
}
