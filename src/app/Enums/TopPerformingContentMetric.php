<?php

namespace App\Enums;

class TopPerformingContentMetric extends Enum
{
    protected static $content = [
        'top_most_reads' => [
            'name' => 'top-most-reads',
            'field_mapping' => 'view_count',
        ],
        'top_most_likes' => [
            'name' => 'top-most-likes',
            'field_mapping' => 'like_count',
        ],
        'top_most_comments' => [
            'name' => 'top-most-comment',
            'field_mapping' => 'comment_count',
        ],
        'top_most_shares' => [
            'name' => 'top-most-shares',
            'field_mapping' => 'share_count',
        ],
    ];
}
