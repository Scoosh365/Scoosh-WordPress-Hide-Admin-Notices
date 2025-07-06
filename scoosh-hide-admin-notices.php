<?php
/**
 * Plugin Name: Scoosh - Hide Admin Notices
 * Description: Adds a toggle under a "Scoosh" menu to hide or show plugin and theme admin notices.
 * Version: 1.1
 * Author: Scoosh
 */

defined('ABSPATH') || exit;

// Option name to store toggle status
define('SCOOSH_HIDE_NOTICES_OPTION', 'scoosh_hide_admin_notices');

// Hide admin notices if enabled
add_action('admin_init', function () {
    if (get_option(SCOOSH_HIDE_NOTICES_OPTION) === '1') {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}, 100);

// Add "Scoosh" top-level menu and "Hide Admin Notices" submenu
add_action('admin_menu', function () {
    if (!menu_page_url('scoosh_dashboard', false)) {
        add_menu_page('Scoosh', 'Scoosh', 'manage_options', 'scoosh_dashboard', '__return_null', 'dashicons-admin-generic', 65);
    }

    add_submenu_page(
        'scoosh_dashboard',
        'Hide Admin Notices',
        'Hide Admin Notices',
        'manage_options',
        'scoosh-hide-notices',
        'scoosh_hide_notices_page'
    );
});

// Admin page HTML for toggling notices
function scoosh_hide_notices_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if (isset($_POST['scoosh_toggle_submit']) && check_admin_referer('scoosh_toggle_notices')) {
        $enabled = isset($_POST['scoosh_hide_notices']) ? '1' : '0';
        update_option(SCOOSH_HIDE_NOTICES_OPTION, $enabled);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $checked = get_option(SCOOSH_HIDE_NOTICES_OPTION) === '1' ? 'checked' : '';
    ?>

    <div class="wrap">
        <h1>Hide Admin Notices</h1>
        <form method="post">
            <?php wp_nonce_field('scoosh_toggle_notices'); ?>
            <label>
                <input type="checkbox" name="scoosh_hide_notices" value="1" <?php echo $checked; ?> />
                Hide all admin notices
            </label>
            <br><br>
            <input type="submit" name="scoosh_toggle_submit" class="button-primary" value="Save Changes">
        </form>
    </div>

    <?php
}
