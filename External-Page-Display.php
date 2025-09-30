<?php
/*
Plugin Name: Magazine Page Display
Description: Adds a feature to select specific pages to broadcast content and provides an embed code for external sites.
Version: 1.4
Author: Your Name
License: GPL2
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
    ?>
    <label for="mpd_broadcast">
        <input type="checkbox" name="mpd_broadcast" id="mpd_broadcast" value="1" <?php checked($broadcast, '1'); ?> />
        Yes, broadcast this page
    </label>
    <br />
    <?php if ($broadcast == '1'): ?>
        <p style="margin-top: 15px;"><strong>Embed Code:</strong></p>
        <p style="font-size: 12px; color: #666;">Copy and paste this code into any webpage to display this page's content:</p>
        <textarea rows="10" style="width:100%; font-size: 11px; font-family: monospace;" readonly onclick="this.select();"><!-- Magazine Page Display Embed -->
<div id="magazine-broadcast-content"></div>
<script type="text/javascript">
(function() {
    const pageId = "<?php echo esc_js($post->ID); ?>";
    const apiUrl = "<?php echo esc_url(get_rest_url(null, 'magazine/v1/broadcast/')); ?>" + pageId;
    
    // Add CSS styles
    const style = document.createElement('style');
    style.textContent = `
        #magazine-broadcast-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        #magazine-broadcast-content h1,
        #magazine-broadcast-content h2,
        #magazine-broadcast-content h3,
        #magazine-broadcast-content h4,
        #magazine-broadcast-content h5,
        #magazine-broadcast-content h6,
        #magazine-broadcast-content .source-title {
            text-align: center;
        }
        #magazine-broadcast-content .wp-block-group {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 100%;
        }
        #magazine-broadcast-content .source-content {
            margin: 0 auto;
            padding: 20px;
            max-width: 100%;
        }
        #magazine-broadcast-content .feature-image {
            width: 100%;
            max-width: 1200px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }
        #magazine-broadcast-content .source-article {
            width: 100%;
        }
        #magazine-broadcast-content img {
            max-width: 100%;
            height: auto;
        }
    `;
    document.head.appendChild(style);
    
    // Fetch and display content
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Content not available');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.title && data.content) {
                const featureImage = data.feature_image ? 
                    `<img src="${data.feature_image}" alt="Feature Image" class="feature-image">` : "";
                document.getElementById("magazine-broadcast-content").innerHTML = `
                    <div class="source-article">
                        ${featureImage}
                        <h1 class="source-title">${data.title}</h1>
                        <div class="source-content">${data.content}</div>
                    </div>
                `;
            } else {
                document.getElementById("magazine-broadcast-content").innerHTML = 
                    "<p>No content found.</p>";
            }
        })
        .catch(error => {
            console.error('Magazine Display Error:', error);
            document.getElementById("magazine-broadcast-content").innerHTML = 
                "<p>Error loading content. Please try again later.</p>";
        });
})();
</script>
<!-- End Magazine Page Display Embed --></textarea>
        <p style="font-size: 12px; color: #666; margin-top: 10px;">
            <strong>Note:</strong> The page must remain published and the "Broadcast" checkbox must stay checked for the embed to work.
        </p>
    <?php endif; ?>
    <?php
}

// Save the broadcast setting
function mpd_save_broadcast_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['mpd_broadcast_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['mpd_broadcast_nonce'], 'mpd_broadcast_nonce_action')) {
        return;
    }
    
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save or delete the broadcast setting
    if (isset($_POST['mpd_broadcast'])) {
        update_post_meta($post_id, '_mpd_broadcast', '1');
    } else {
        delete_post_meta($post_id, '_mpd_broadcast');
    }
}
add_action('save_post', 'mpd_save_broadcast_meta');

// Register REST API endpoint for broadcasted pages
add_action('rest_api_init', function() {
    register_rest_route('magazine/v1', '/broadcast/(?P<page_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'mpd_get_broadcast_content',
        'permission_callback' => '__return_true', // Public endpoint
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

    // Check if page exists, is published, and is marked for broadcast
    if (!$page || $page->post_status !== 'publish' || get_post_meta($page_id, '_mpd_broadcast', true) != '1') {
        return new WP_Error(
            'not_found', 
            'Page not found or not available for broadcast', 
            array('status' => 404)
        );
    }

    // Return sanitized content
    return array(
        'title'   => wp_kses_post($page->post_title),
        'content' => apply_filters('the_content', $page->post_content),
        'feature_image' => get_the_post_thumbnail_url($page_id, 'full') ?: '',
    );
}

// Add admin notices for feedback
function mpd_admin_notices() {
    $screen = get_current_screen();
    if ($screen->id === 'page' && isset($_GET['message']) && $_GET['message'] == 1) {
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
