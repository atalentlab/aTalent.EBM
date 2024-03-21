<?php

namespace App\Enums;

class UserRejectReason extends Enum
{
    protected static $content = [
        'reason_a' => [
            'name' => 'Reason A',
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam luctus rhoncus lacus, non elementum odio condimentum quis. Vestibulum ante ipsum primis in faucibus.',
        ],
        'reason_b' => [
            'name' => 'Reason B',
            'message' => 'Sed vel auctor urna, a pharetra mi. Fusce finibus lacus vel rutrum sollicitudin.',
        ],
        'reason_c' => [
            'name' => 'Reason C',
            'message' => 'Praesent vestibulum nisi nisi, ac vulputate mauris dignissim eu.',
        ],
        'other' => [
            'name' => 'Other',
            'message' => null,
        ],
    ];
}
