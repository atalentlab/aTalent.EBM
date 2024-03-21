<?php

namespace App\Enums;

class AdminNotificationType extends Enum
{
    protected static $content = [
        'new_user'      => 'New user registration',
        'update_data'   => 'Update data request',
    ];
}
