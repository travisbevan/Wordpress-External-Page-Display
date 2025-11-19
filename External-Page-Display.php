<?php
/*
Plugin Name: External Page Display
Description: Adds a feature to select specific pages to broadcast content and provides an embed code for external sites.
Version: 1.5
Author: Travis Bevan
Author URI: https://travisbevan.com
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add a meta box for selecting broadcast option
function mpd_add_broadcast_meta_box() {
    add_meta_box(
        'mpd_broadcast_meta_box',
        'Broadcast Page',
        'mpd_broadcast_meta_box_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'mpd_add_broadcast_meta_box');

// Callback function for the meta box
function mpd_broadcast_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('mpd_broadcast_nonce_action', 'mpd_broadcast_nonce');
    
    // Retrieve current value
    $broadcast = get_post_meta($post->ID, '_mpd_broadcast', true);
    $embed_version = get_option('mpd_embed_version', '1.5');
    ?>
    <label for="mpd_broadcast">
        <input type="checkbox" name="mpd_broadcast" id="mpd_broadcast" value="1" <?php checked($broadcast, '1'); ?> />
        Yes, broadcast this page
    </label>
    <br />
    <?php if ($broadcast == '1'): ?>
        <p style="margin-top: 15px;"><strong>Embed Code (v<?php echo esc_html($embed_version); ?>):</strong></p>
        <p style="font-size: 12px; color: #666;">Copy and paste this code into any webpage:</p>
        <textarea rows="6" style="width:100%; font-size: 11px; font-family: monospace;" readonly onclick="this.select();"><!-- Magazine Page Display Embed -->
<div id="magazine-broadcast-content"></div>
<script src="<?php echo esc_url(plugins_url('embed.js', __FILE__)); ?>?page=<?php echo esc_attr($post->ID); ?>&api=<?php echo urlencode(get_rest_url(null, 'magazine/v1/broadcast/')); ?>&v=<?php echo esc_attr($embed_version); ?>"></script>
<!-- End Magazine Page Display Embed --></textarea>
        <p style="font-size: 12px; color: #0073aa; margin-top: 10px;">
            <strong>✨ New in v1.5:</strong> External JavaScript file means plugin updates work automatically - no need to update embed codes!
        </p>
        <p style="font-size: 12px; color: #666; margin-top: 5px;">
            The page must remain published and broadcasted for the embed to work.
        </p>
    <?php endif; ?>
    <?php
}

// Save the broadcast setting
function mpd_save_broadcast_meta($post_id) {
    if (!isset($_POST['mpd_broadcast_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['mpd_broadcast_nonce'], 'mpd_broadcast_nonce_action')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['mpd_broadcast'])) {
        update_post_meta($post_id, '_mpd_broadcast', '1');
    } else {
        delete_post_meta($post_id, '_mpd_broadcast');
    }
}
add_action('save_post', 'mpd_save_broadcast_meta');

// Register REST API endpoint
add_action('rest_api_init', function() {
    register_rest_route('magazine/v1', '/broadcast/(?P<page_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'mpd_get_broadcast_content',
        'permission_callback' => '__return_true',
        'args' => array(
            'page_id' => array(
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
});

// Callback for REST API endpoint
function mpd_get_broadcast_content($data) {
    $page_id = (int) $data['page_id'];
    $page = get_post($page_id);

    if (!$page || $page->post_status !== 'publish' || get_post_meta($page_id, '_mpd_broadcast', true) != '1') {
        return new WP_Error(
            'not_found', 
            'Page not found or not available for broadcast', 
            array('status' => 404)
        );
    }

    // Get content and ensure links open in new tabs
    $content = apply_filters('the_content', $page->post_content);
    $content = mpd_fix_links($content);

    return array(
        'title'   => wp_kses_post($page->post_title),
        'content' => $content,
        'feature_image' => get_the_post_thumbnail_url($page_id, 'full') ?: '',
        'version' => '1.5',
    );
}

// Fix links to open in new tabs and preserve onclick
function mpd_fix_links($content) {
    // Add target="_blank" to all links without it
    $content = preg_replace_callback(
        '/<a\s+([^>]*?)>/i',
        function($matches) {
            $attrs = $matches[1];
            
            // Only modify if href exists
            if (stripos($attrs, 'href=') === false) {
                return $matches[0];
            }
            
            // Add target if missing
            if (stripos($attrs, 'target=') === false) {
                $attrs .= ' target="_blank"';
            }
            
            // Add rel if missing
            if (stripos($attrs, 'rel=') === false) {
                $attrs .= ' rel="noopener noreferrer"';
            }
            
            return '<a ' . trim($attrs) . '>';
        },
        $content
    );
    
    return $content;
}

// Add admin notices
function mpd_admin_notices() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'page' && isset($_GET['message']) && $_GET['message'] == 1) {
        $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
        if ($post_id && get_post_meta($post_id, '_mpd_broadcast', true) == '1') {
            ?>
            <div class="notice notice-info is-dismissible">
                <p><strong>Magazine Page Display:</strong> This page is now available for broadcast. Check the sidebar for the embed code.</p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'mpd_admin_notices');

// Set version on activation
function mpd_activate() {
    update_option('mpd_embed_version', '1.5');
}
register_activation_hook(__FILE__, 'mpd_activate');
