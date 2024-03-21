<?php

namespace App\Enums;

class Metric extends Enum
{
    protected static $content = [
        'follower_count_difference' => [
            'name' => 'new-fans',
            'help' => 'This is the estimated number of fans gained in the selected period.',
        ],
        'post_count_difference' => [
            'name' => 'posts',
            'help' => 'This is the total number of articles & newsletters posted across all tracked channels, in the selected period.'
        ],
        'view_count_difference' => [
            'name' => 'reads-wechat',
            'help' => 'This is the total number of reads/views, on all new social articles & newsletters, posted in the selected period.',
        ],
        'avg_view_count' => [
            'name' => 'avg-reads-wechat',
            'help' => 'This is the average reads (or views) for each post - the higher the number, the better, meaning your followers regularly read your content.',
        ],
        'reads_vs_fans' => [
            'name' => 'reads-vs-fans',
            'help' => 'This is the Read vs Popularity Ratio that demonstrates the quality of your content compared to your reach. High-quality content tends to be tailored to an audience (segments) and includes very clear benefits for this audience.',
        ],
        'like_count_difference' => [
            'name' => 'likes',
            'help' => 'This is the total number of likes collected in the selected period.',
        ],
        'avg_like_count' => [
            'name' => 'avg-likes',
            'help' => 'This is the average number of like collected per post, in the selected period. Great content gets engagement and conversions.',
        ],
        'likes_vs_fans' => [
            'name' => 'likes-vs-fans',
            'help' => 'This is the Like vs Popularity Ratio that demonstrates the transforming impact of your content versus your popularity. Impactful and loved content contains very clear steps, know-how, useful tips, and genuine guidance.',
        ],
        'share_count_difference' => [
            'name' => 'shares',
            'help' => 'This is the combined number of Wows (WeChat), and shares (Weibo, LinkedIn) generated in the selected period.',
        ],
        'avg_share_count' => [
            'name' => 'avg-shares',
            'help' => 'This is the average number of shares or wows per post, in the selected period.',
        ],
        'shares_vs_fans' => [
            'name' => 'shares-vs-fans',
            'help' => 'This is the Share vs Popularity Ratio that reflects the virality of your content. This helps with growing your follower base and outreaching friends of friends of your target audience. The higher the better.',
        ],
        'avg_engagement_vs_posts' => [
            'name' => 'avg-engagement-vs-posts-ratio',
            'help' => 'This is the ultimate metric, measuring how much engagement your team is able to get from their social posting (activity). You want your team to spend their time producing fewer posts (quantity) but getting higher engagement (quality). Best ROI is reflected with a higher than average Engagement vs Post ratio.',
        ],
    ];
}
