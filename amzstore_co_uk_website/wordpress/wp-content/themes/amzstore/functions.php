<?php
/** AMZ Store theme setup. */
if (!defined('ABSPATH')) { exit; }
function amzstore_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('custom-logo', array('height'=>80,'width'=>260,'flex-height'=>true,'flex-width'=>true));
    register_nav_menus(array('primary'=>__('Primary Menu','amzstore')));
}
add_action('after_setup_theme','amzstore_setup');
function amzstore_assets() {
    wp_enqueue_style('amzstore-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts','amzstore_assets');
function amzstore_menu_fallback() {
    echo '<div class="nav-inner container"><a href="'.esc_url(home_url('/used-parts/')).'">Used parts</a><a href="'.esc_url(home_url('/new-parts/')).'">New parts</a><a href="'.esc_url(home_url('/compare-prices/')).'">Compare prices</a><a href="'.esc_url(home_url('/vehicle-search/')).'">Find parts for my car</a><a href="'.esc_url(home_url('/contact/')).'">Help & contact</a></div>';
}
function amzstore_cart_count() {
    if (class_exists('WooCommerce') && WC()->cart) return WC()->cart->get_cart_contents_count();
    return 0;
}
