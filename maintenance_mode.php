<?php
/*
Plugin Name: WP Maintenance Mode
Plugin URI: https://github.com/meddil/wp_maintenance_mode
Description: Maintenance make your website temporarily unavailable.
Version: 0.0.1
Author: Med Maaoui
Author URI: https://github.com/meddil
*/

//dashboard shortcut
function wmm_add_menu_item() {
    add_menu_page(
        'Maintenance Mode',
        'Maintenance Mode',
        'manage_options',
        'wmm-settings',
        'wmm_settings_page',
        'dashicons-admin-tools',
        99
    );
}
add_action('admin_menu', 'wmm_add_menu_item');

// settings page callback function
function wmm_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['wmm_toggle'])) {
        update_option('wmm_maintenance_mode', $_POST['wmm_toggle']);
    }

    $maintenance_mode = get_option('wmm_maintenance_mode', 'off');
    ?>
    <div class="wrap">
        <h2>Maintenance Mode</h2>
        <form method="post" action="">
            <label for="wmm_toggle">Maintenance Mode:</label>
            <input type="checkbox" name="wmm_toggle" id="wmm_toggle" <?php echo ($maintenance_mode === 'on') ? 'checked' : ''; ?>>
            <input type="submit" class="button-primary" value="Save">
        </form>
    </div>
    <?php
}

//force maintenance mode
function wmm_maintenance_redirect() {
    $maintenance_mode = get_option('wmm_maintenance_mode', 'off');
    if ($maintenance_mode === 'on' && !current_user_can('manage_options')) {
        wp_die('The website is under maintenance and will be available soon');
    }
}
add_action('template_redirect', 'wmm_maintenance_redirect');
