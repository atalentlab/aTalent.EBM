<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database seeder counts
    |--------------------------------------------------------------------------
    |
    */
    'seeder' => [
        'users'             => (int) env('SEEDER_USERS', 0),
        'organizations'     => (int) env('SEEDER_ORGANIZATIONS', 0),
        'organization_data' => (bool) env('SEEDER_ORGANIZATION_DATA', false), // per period per organization
        'posts'             => (int) env('SEEDER_POSTS', 0),
        'post_data'         => (bool) env('SEEDER_POST_DATA', false), // per period per post
        'industries'        => (int) env('SEEDER_INDUSTRIES', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Uploaded images browser cache lifetime
    |--------------------------------------------------------------------------
    |
    */
    'image-browser-cache'   => 60 * 60 * 24 * 30, // 30 days

    /*
    |--------------------------------------------------------------------------
    | Weights for each engagement action to determine score
    |--------------------------------------------------------------------------
    |
    */
    'scores' => [
        'view'      => 1,
        'like'      => 3,
        'comment'   => 5,
        'share'     => 7,
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable WeChat API features such as customized share message
    |--------------------------------------------------------------------------
    |
    */

    'enable-wechat-api' => (bool) env('WECHAT_API_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Enable EDM API
    |--------------------------------------------------------------------------
    |
    | Send newsletter and contact forms subscribers to EDM provider
    */

    'enable-edm-api' => (bool) env('EDM_API_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Admin Notifications Email
    |--------------------------------------------------------------------------
    |
    | Email address used to send CMS notifications to
    */

    'admin-notifications-email' => env('ADMIN_NOTIFICATIONS_EMAIL', 'hello@employerbrandingmonitor.com'),

    /*
    |--------------------------------------------------------------------------
    | Crawler Report Email
    |--------------------------------------------------------------------------
    |
    | Email address used to send Crawler reports to
    */

    'crawler-report-email' => env('CRAWLER_REPORT_EMAIL', 'hello@employerbrandingmonitor.com'),

    /*
    |--------------------------------------------------------------------------
    | Support Email
    |--------------------------------------------------------------------------
    |
    | Email to send support enquiries to
    */

    'support-email' => env('SUPPORT_EMAIL', 'hello@employerbrandingmonitor.com'),

    /*
    |--------------------------------------------------------------------------
    | Google Tag Manager Container Code
    |--------------------------------------------------------------------------
    |
    */

    'gtm-container-code' => env('GTM_CONTAINER_CODE'),
];
