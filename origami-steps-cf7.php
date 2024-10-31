<?php
/*
Plugin Name: Origami For Contact Form 7
Description: Displays an origami steps animation next to Contact Form 7 forms as the user fills in fields.
Version: 1.1
Author: Anowar Hossin Rana
Author URI: https://cxrana.wordpress.com
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;

// Enqueue Scripts and Styles
function origami_cf7_enqueue_scripts() {
    wp_enqueue_style('origami-cf7-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('origami-cf7-script', plugin_dir_url(__FILE__) . 'js/frontend.js', array('jquery'), '1.0', true);

    // Get the selected origami type
    $origami_type = get_option('origami_type', 'tree');

    // Determine which images to pass based on the origami type
    if ($origami_type === 'custom') {
        $origami_parts = array(
            'part1' => esc_url(wp_get_attachment_url(get_option('origami_part1'))),
            'part2' => esc_url(wp_get_attachment_url(get_option('origami_part2'))),
            'part3' => esc_url(wp_get_attachment_url(get_option('origami_part3'))),
            'part4' => esc_url(wp_get_attachment_url(get_option('origami_part4')))
        );
    } elseif ($origami_type === 'bird') {
        $origami_parts = array(
            'part1' => plugin_dir_url(__FILE__) . 'images/bird_part1.png',
            'part2' => plugin_dir_url(__FILE__) . 'images/bird_part2.png',
            'part3' => plugin_dir_url(__FILE__) . 'images/bird_part3.png',
            'part4' => plugin_dir_url(__FILE__) . 'images/bird_part4.png'
        );
    } elseif ($origami_type === 'camel') {
        $origami_parts = array(
            'part1' => plugin_dir_url(__FILE__) . 'images/camel_part1.png',
            'part2' => plugin_dir_url(__FILE__) . 'images/camel_part2.png',
            'part3' => plugin_dir_url(__FILE__) . 'images/camel_part3.png',
            'part4' => plugin_dir_url(__FILE__) . 'images/camel_part4.png'
        );
    } elseif ($origami_type === 'boat') {
        $origami_parts = array(
            'part1' => plugin_dir_url(__FILE__) . 'images/boat_part1.png',
            'part2' => plugin_dir_url(__FILE__) . 'images/boat_part2.png',
            'part3' => plugin_dir_url(__FILE__) . 'images/boat_part3.png',
            'part4' => plugin_dir_url(__FILE__) . 'images/boat_part4.png'
        );
    } elseif ($origami_type === 'business') {
        $origami_parts = array(
            'part1' => plugin_dir_url(__FILE__) . 'images/business_part1.png',
            'part2' => plugin_dir_url(__FILE__) . 'images/business_part2.png',
            'part3' => plugin_dir_url(__FILE__) . 'images/business_part3.png',
            'part4' => plugin_dir_url(__FILE__) . 'images/business_part4.png'
        );
    } else { // Default to 'tree'
        $origami_parts = array(
            'part1' => plugin_dir_url(__FILE__) . 'images/tree_part1.png',
            'part2' => plugin_dir_url(__FILE__) . 'images/tree_part2.png',
            'part3' => plugin_dir_url(__FILE__) . 'images/tree_part3.png',
            'part4' => plugin_dir_url(__FILE__) . 'images/tree_part4.png'
        );
    }

    // Pass images to JavaScript
    wp_localize_script('origami-cf7-script', 'origamiParts', $origami_parts);
}


add_action('wp_enqueue_scripts', 'origami_cf7_enqueue_scripts');

// Enqueue admin scripts and styles
function origami_cf7_admin_enqueue_scripts($hook) {
    if ($hook !== 'toplevel_page_origami-cf7-settings') {
        return;
    }

    // Enqueue the WordPress media uploader
    wp_enqueue_media();
    
    // Enqueue custom admin CSS
    wp_enqueue_style('origami-cf7-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
    
    // Enqueue custom admin JS
    wp_enqueue_script('origami-cf7-admin-script', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'origami_cf7_admin_enqueue_scripts');


// Add admin settings page
function origami_cf7_settings_page() {
    add_menu_page(
        'Origami CF7 Settings', // Page title
        'Origami CF7', // Menu title
        'manage_options', // Capability
        'origami-cf7-settings', // Menu slug
        'origami_cf7_settings_page_html', // Callback function
        'dashicons-camera', // Icon set to tree
        100 // Position
    );
}
add_action('admin_menu', 'origami_cf7_settings_page');


// Admin settings page HTML
function origami_cf7_settings_page_html() {
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['save_origami_settings'])) {
        update_option('origami_type', sanitize_text_field($_POST['origami_type']));
        update_option('origami_part1', absint($_POST['origami_part1']));
        update_option('origami_part2', absint($_POST['origami_part2']));
        update_option('origami_part3', absint($_POST['origami_part3']));
        update_option('origami_part4', absint($_POST['origami_part4']));
	    update_option('origami_caption', sanitize_textarea_field($_POST['origami_caption'])); // Save caption

        echo '<div class="updated"><p>Settings saved!</p></div>';
    }

    $origami_type = get_option('origami_type', 'tree'); // Default to 'tree'
    $part1 = get_option('origami_part1', '');
    $part2 = get_option('origami_part2', '');
    $part3 = get_option('origami_part3', '');
    $part4 = get_option('origami_part4', '');

    ?>
    <div class="wrap">
        <h1>Origami CF7 Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th>Origami Type</th>
                    <td>
                        <select name="origami_type" id="origami_type">
    <option value="tree" <?php selected($origami_type, 'tree'); ?>>Tree</option>
    <option value="bird" <?php selected($origami_type, 'bird'); ?>>Bird</option>
    <option value="camel" <?php selected($origami_type, 'camel'); ?>>Camel</option>
    <option value="boat" <?php selected($origami_type, 'boat'); ?>>Boat</option>
    <option value="business" <?php selected($origami_type, 'business'); ?>>Business</option>
    <option value="custom" <?php selected($origami_type, 'custom'); ?>>Custom</option>
</select>

                    </td>
                </tr>
				<tr>
    <th>Origami Caption</th>
    <td>
        <textarea name="origami_caption" id="origami_caption" rows="4" cols="50"><?php echo esc_textarea(get_option('origami_caption', '')); ?></textarea>
        <p class="description">Enter the caption for the origami display.</p>
    </td>
</tr>

				
                <tr>
                    <th>Step 1 Image</th>
                    <td>
                        <input type="hidden" name="origami_part1" id="origami_part1" value="<?php echo esc_attr($part1); ?>" />
                        <button type="button" class="button upload-image-button" data-target="#origami_part1">Upload Image</button>
                        <div class="image-preview" id="preview_origami_part1">
                            <?php if ($part1) echo wp_get_attachment_image($part1, 'thumbnail'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Step 2 Image</th>
                    <td>
                        <input type="hidden" name="origami_part2" id="origami_part2" value="<?php echo esc_attr($part2); ?>" />
                        <button type="button" class="button upload-image-button" data-target="#origami_part2">Upload Image</button>
                        <div class="image-preview" id="preview_origami_part2">
                            <?php if ($part2) echo wp_get_attachment_image($part2, 'thumbnail'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Step 3 Image</th>
                    <td>
                        <input type="hidden" name="origami_part3" id="origami_part3" value="<?php echo esc_attr($part3); ?>" />
                        <button type="button" class="button upload-image-button" data-target="#origami_part3">Upload Image</button>
                        <div class="image-preview" id="preview_origami_part3">
                            <?php if ($part3) echo wp_get_attachment_image($part3, 'thumbnail'); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Step 4 Image</th>
                    <td>
                        <input type="hidden" name="origami_part4" id="origami_part4" value="<?php echo esc_attr($part4); ?>" />
                        <button type="button" class="button upload-image-button" data-target="#origami_part4">Upload Image</button>
                        <div class="image-preview" id="preview_origami_part4">
                            <?php if ($part4) echo wp_get_attachment_image($part4, 'thumbnail'); ?>
                        </div>
                    </td>
                </tr>
            </table>
            <p><input type="submit" name="save_origami_settings" class="button-primary" value="Save Settings" /></p>
        </form>
    <!-- Display the shortcode example here -->
    <div style="border: 1px solid #ddd; padding: 20px; margin: 20px 0; background-color: #f9f9f9;">
        <strong>Example Shortcode:</strong> 
        <code>[origami_cf7 id="96b3afb"]</code>
        <p style="margin-top: 10px;">Replace the <strong>"96b3afb"</strong> with the ID of your Contact Form 7 form.</p>
    </div>
</div>
	
    <?php
}

// Add shortcode to display origami next to CF7 forms
function origami_cf7_display_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => ''
    ), $atts);

    if (empty($atts['id'])) {
        return '<p>Error: Please provide a valid Contact Form 7 ID.</p>';
    }

    $origami_type = get_option('origami_type', 'tree');
    $origami_images = [];

    if ($origami_type === 'custom') {
        $origami_images = [
            esc_url(wp_get_attachment_url(get_option('origami_part1'))),
            esc_url(wp_get_attachment_url(get_option('origami_part2'))),
            esc_url(wp_get_attachment_url(get_option('origami_part3'))),
            esc_url(wp_get_attachment_url(get_option('origami_part4')))
        ];
    } else {
        // Default images for other types
        $origami_images = [
            plugin_dir_url(__FILE__) . "images/{$origami_type}_part1.png",
            plugin_dir_url(__FILE__) . "images/{$origami_type}_part2.png",
            plugin_dir_url(__FILE__) . "images/{$origami_type}_part3.png",
            plugin_dir_url(__FILE__) . "images/{$origami_type}_part4.png"
        ];
    }

   $origami_caption = get_option('origami_caption', ''); // Retrieve the caption

ob_start();
?>
<div class="origami-cf7-container">
    <div id="origami-container">
        <?php if (!empty($origami_images)): ?>
            <div id="origami-step-image" style="background-image: url('<?php echo esc_url($origami_images[0]); ?>');"></div>
        <?php else: ?>
            <div>No origami images available.</div>
        <?php endif; ?>

        <?php if (!empty($origami_caption)): ?>
            <div id="origami-caption" class="origami-caption">
                <?php echo esc_html($origami_caption); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="cf7-form-container">
        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($atts['id']) . '"]'); ?>
    </div>
</div>
<?php
return ob_get_clean();

}

add_shortcode('origami_cf7', 'origami_cf7_display_shortcode');
