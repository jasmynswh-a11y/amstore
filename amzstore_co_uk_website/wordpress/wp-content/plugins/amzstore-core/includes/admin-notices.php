<?php
if (!defined('ABSPATH')) { exit; }
function amzcore_admin_notice() {
    if (!current_user_can('activate_plugins') || class_exists('WooCommerce')) return;
    echo '<div class="notice notice-warning"><p><strong>AMZ Store Core:</strong> Install and activate WooCommerce to enable product listings, basket and checkout. AMZ Store Core adds specialist inventory and price-reference fields to WooCommerce products.</p></div>';
}
add_action('admin_notices','amzcore_admin_notice');
