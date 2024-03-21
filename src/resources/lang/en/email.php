<?php

return [
    'general' => [
        'from' => [
            'name' => 'Eric from the EBM Team',
        ],
        'signature' => [
            'name' => 'Eric',
        ],
    ],
    'reports' => [
        'my-organization' => [
            'subject' => 'Your EBM monthly report is ready! Click to see more',
            'greeting' => 'Hi :name,',
            'line-1' => 'Here is a quick overview of your recent social recruitment efforts, summarizing your social media activity, popularity and engagement for :organization.',
            'cta' => 'Click here to see more',
            'salutation' => 'Best regards,',
        ],
        'competitor' => [
            'subject' => 'Your EBM weekly competitor report is ready! Click to see more',
            'greeting' => 'Hi :name,',
            'line-1' => 'Here is a quick overview of last week\'s social recruitment efforts, summarizing your social media activity, popularity and engagement for :organization compared with :competitor.',
            'line-1-alt' => 'Here is a quick overview of last week\'s social recruitment efforts, summarizing your social media activity, popularity and engagement for :organization.',
            'line-2' => 'You can also include a competitor in this report by adding one in your <a href=":link">organization page</a>.',
            'cta' => 'Click here to see more',
            'salutation' => 'Best regards,',
            'data' => [
                'progress' => 'Progress',
                'metric' => 'Metric',
                'posts' => 'Posts',
                'views' => 'Reads',
                'likes' => 'Likes',
                'comments' => 'Comments',
                'shares' => 'Shares/Wows',
                'followers' => 'Est. Total Fan Base',
                'na' => 'N/A',
            ],
        ],
    ],
];
