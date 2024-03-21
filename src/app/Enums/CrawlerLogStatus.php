<?php

namespace App\Enums;

class CrawlerLogStatus extends Enum
{
    protected static $content = [
        'success'       => 'Success',
        'error'         => 'Error',
    ];
}
