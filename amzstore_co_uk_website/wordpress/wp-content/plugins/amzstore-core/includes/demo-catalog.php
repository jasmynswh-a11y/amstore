<?php
if (!defined('ABSPATH')) { exit; }
/** External seeded placeholder photos are for local layout testing only. */
function amzcore_demo_image_url($seed) {
    return 'https://picsum.photos/seed/amzstore-part-' . absint($seed) . '/720/540';
}
function amzcore_demo_image_filter($html, $product, $size, $attr, $placeholder) {
    if (!$product || !is_object($product)) return $html;
    $url = get_post_meta($product->get_id(), '_amz_demo_image_url', true);
    if (!$url) return $html;
    $alt = esc_attr($product->get_name() . ' — demo placeholder image');
    $classes = 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail amz-demo-external-image';
    return '<img src="' . esc_url($url) . '" alt="' . $alt . '" class="' . esc_attr($classes) . '" loading="lazy" decoding="async" />';
}
add_filter('woocommerce_product_get_image', 'amzcore_demo_image_filter', 20, 5);
function amzcore_demo_catalog_admin_menu() {
    add_submenu_page('woocommerce', 'AMZ Demo Catalogue', 'AMZ Demo Catalogue', 'manage_woocommerce', 'amz-demo-catalogue', 'amzcore_demo_catalog_page');
}
add_action('admin_menu', 'amzcore_demo_catalog_admin_menu');
function amzcore_demo_catalog_page() {
    if (!current_user_can('manage_woocommerce')) return;
    $created = isset($_GET['amz_demo_created']) ? absint($_GET['amz_demo_created']) : 0;
    ?>
    <div class="wrap"><h1>AMZ Store — 400-image demo catalogue</h1>
    <p>This creates 200 used and 200 new sample listings. Each product gets its own seeded external placeholder photo. The photos are general test photography, not verified car-part images. Do not use these listings for real sales.</p>
    <?php if ($created) echo '<div class="notice notice-success"><p>Created ' . esc_html($created) . ' demo products. Visit the Shop page to check the product grid and image loading.</p></div>'; ?>
    <p><strong>Before running:</strong> WooCommerce must be active. Existing products are not deleted. Running it again skips demo SKUs that already exist.</p>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <?php wp_nonce_field('amzcore_create_demo_catalogue'); ?>
      <input type="hidden" name="action" value="amzcore_create_demo_catalogue">
      <?php submit_button('Create 400 demo products with 400 image links', 'primary', 'submit', false); ?>
    </form>
    <hr><p>Image provider: <a href="https://picsum.photos/" target="_blank" rel="noopener">Lorem Picsum</a>. Images load from the internet; your local WordPress site must be online to display them.</p></div>
    <?php
}
function amzcore_create_demo_catalogue() {
    if (!current_user_can('manage_woocommerce')) wp_die('Permission denied.');
    check_admin_referer('amzcore_create_demo_catalogue');
    if (!class_exists('WooCommerce')) wp_die('Please activate WooCommerce first.');
    $parts = array('Front Headlight','Rear Tail Light','Wing Mirror','Front Bumper','Rear Bumper','Radiator','Alternator','Starter Motor','Door Handle','Window Regulator','Brake Caliper','Brake Disc','Suspension Arm','Shock Absorber','Air Filter Housing','Turbocharger','Intercooler','Fuel Pump','Door Lock Actuator','Cooling Fan');
    $makes = array('Ford Focus','Volkswagen Golf','Vauxhall Astra','BMW 3 Series','Audi A3','Toyota Corolla','Honda Civic','Nissan Qashqai','Mercedes-Benz C-Class','Peugeot 308');
    $created = 0;
    foreach (array('used','new') as $condition) {
        $cat_name = $condition === 'used' ? 'Used Parts' : 'New Parts';
        $term = term_exists($cat_name, 'product_cat');
        if (!$term) $term = wp_insert_term($cat_name, 'product_cat');
        $cat_id = is_array($term) ? (int)$term['term_id'] : (int)$term;
        for ($i=0; $i<200; $i++) {
            $number = ($condition === 'used' ? 1 : 201) + $i;
            $sku = 'AMZ-DEMO-' . strtoupper($condition) . '-' . str_pad((string)($i+1), 3, '0', STR_PAD_LEFT);
            if (wc_get_product_id_by_sku($sku)) continue;
            $part = $parts[$i % count($parts)];
            $make = $makes[(int)floor($i / 2) % count($makes)];
            $title = '[DEMO] ' . $part . ' — ' . $make . ' — ' . strtoupper($condition);
            $product = new WC_Product_Simple();
            $product->set_name($title);
            $product->set_sku($sku);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_description('DEMO LISTING FOR LOCAL DISPLAY TESTING ONLY. This is sample catalogue content. Confirm exact OE number, vehicle variant, condition, stock and compatibility before any real listing or sale.');
            $product->set_short_description('Demo ' . $condition . ' part. Sample data — not a live stock offer.');
            $product->set_regular_price($condition === 'used' ? (string)(25 + (($i * 7) % 220)) : (string)(55 + (($i * 11) % 420)));
            $product->set_stock_status('instock');
            $product->set_category_ids($cat_id ? array($cat_id) : array());
            $id = $product->save();
            update_post_meta($id, '_amz_demo_image_url', amzcore_demo_image_url($number));
            update_post_meta($id, '_amz_demo_listing', 'yes');
            update_post_meta($id, '_amz_stock_source', 'owned');
            update_post_meta($id, '_amz_vehicle_fitment', $make . ' (sample fitment — verify before sale)');
            update_post_meta($id, '_amz_part_number', 'DEMO-OE-' . str_pad((string)$number, 5, '0', STR_PAD_LEFT));
            if ($condition === 'used') {
                update_post_meta($id, '_amz_condition', array('A','B','C')[$i % 3]);
                update_post_meta($id, '_amz_test_status', 'not-tested');
                update_post_meta($id, '_amz_defects', 'Demo listing: condition and defects are not verified.');
            }
            $created++;
        }
    }
    wp_safe_redirect(add_query_arg('amz_demo_created', $created, admin_url('admin.php?page=amz-demo-catalogue')));
    exit;
}
add_action('admin_post_amzcore_create_demo_catalogue', 'amzcore_create_demo_catalogue');
