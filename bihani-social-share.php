<?php
/*
Plugin Name: Bihani Social Share
Description: A simple social sharing plugin for WordPress with share counts.
Version: 1.3
Author: Your Name
*/

defined('ABSPATH') or die('No script kiddies please!');

// Enqueue CSS and JS
function bihani_social_share_enqueue() {
    wp_enqueue_style('bihani-social-share-css', plugin_dir_url(__FILE__) . 'bihani-social-share.css');
    wp_enqueue_script('bihani-social-share-js', plugin_dir_url(__FILE__) . 'bihani-social-share.js', array('jquery'), null, true);

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'bihani_social_share_enqueue');

// Function to get share counts
function bihani_get_share_counts($url) {
    $counts = [
        'facebook' => 0,
        'twitter' => 0,
        'linkedin' => 0,
        'reddit' => 0,
        'whatsapp' => 0,
        'telegram' => 0,
        'pinterest' => 0,
    ];

    // Get Facebook share count
    $facebook_response = wp_remote_get("https://graph.facebook.com/?id=" . urlencode($url));
    if (is_array($facebook_response) && !is_wp_error($facebook_response) && isset($facebook_response['body'])) {
        $facebook_data = json_decode($facebook_response['body'], true);
        if (isset($facebook_data['share']['share_count'])) {
            $counts['facebook'] = $facebook_data['share']['share_count'];
        }
    }

    // Get Twitter share count (Dummy since Twitter API requires authentication)
    // You may implement Twitter's API in a production setup if needed.

    // Get LinkedIn share count (Dummy implementation)
    // LinkedIn's share count API has been deprecated, consider omitting or using alternatives.

    // Get Reddit share count
    $reddit_response = wp_remote_get("https://www.reddit.com/api/info.json?url=" . urlencode($url));
    if (is_array($reddit_response) && !is_wp_error($reddit_response) && isset($reddit_response['body'])) {
        $reddit_data = json_decode($reddit_response['body'], true);
        if (isset($reddit_data['data']['children'][0]['data']['ups'])) {
            $counts['reddit'] = $reddit_data['data']['children'][0]['data']['ups'];
        }
    }

    // Other counts (Dummy implementations)
    // You may set specific APIs for WhatsApp, Telegram, Pinterest if necessary.

    return $counts;
}

// Add social share buttons
function bihani_social_share_buttons($content) {
    if (is_single()) {
        $url = get_permalink();
        $title = get_the_title();
        $counts = bihani_get_share_counts($url);

        $share_html = '<div class="bihani-social-share">
            <h4>Share this post:</h4>
            <a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '" target="_blank" class="bihani-share-button facebook">
                <i class="fab fa-facebook-f"></i> <span class="share-count">' . (isset($counts['facebook']) ? $counts['facebook'] : 0) . '</span>
            </a>
            <a href="https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title) . '" target="_blank" class="bihani-share-button twitter">
                <i class="fab fa-twitter"></i> <span class="share-count">' . (isset($counts['twitter']) ? $counts['twitter'] : 0) . '</span>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($url) . '&title=' . urlencode($title) . '" target="_blank" class="bihani-share-button linkedin">
                <i class="fab fa-linkedin-in"></i> <span class="share-count">' . (isset($counts['linkedin']) ? $counts['linkedin'] : 0) . '</span>
            </a>
            <a href="https://wa.me/?text=' . urlencode($title . ' ' . $url) . '" target="_blank" class="bihani-share-button whatsapp">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://www.reddit.com/submit?url=' . urlencode($url) . '&title=' . urlencode($title) . '" target="_blank" class="bihani-share-button reddit">
                <i class="fab fa-reddit-alien"></i> <span class="share-count">' . (isset($counts['reddit']) ? $counts['reddit'] : 0) . '</span>
            </a>
            <a href="https://telegram.me/share/url?url=' . urlencode($url) . '&text=' . urlencode($title) . '" target="_blank" class="bihani-share-button telegram">
                <i class="fab fa-telegram-plane"></i>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&media=' . get_the_post_thumbnail_url() . '&description=' . urlencode($title) . '" target="_blank" class="bihani-share-button pinterest">
                <i class="fab fa-pinterest"></i>
            </a>
        </div>';
        
        return $content . $share_html; // Append share buttons to content
    }
    return $content;
}
add_filter('the_content', 'bihani_social_share_buttons');
?>
