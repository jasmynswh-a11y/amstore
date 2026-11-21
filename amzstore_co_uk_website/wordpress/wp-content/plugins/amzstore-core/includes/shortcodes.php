<?php
if (!defined('ABSPATH')) { exit; }
function amzcore_parts_search_shortcode($atts) {
    if (!class_exists('WooCommerce')) return '<div class="amz-empty">Install WooCommerce to enable the parts catalogue.</div>';
    $atts = shortcode_atts(array('title'=>'Find a car part','limit'=>8),$atts,'amz_parts_search');
    $term = isset($_GET['amz_part_search']) ? sanitize_text_field(wp_unslash($_GET['amz_part_search'])) : '';
    $make = isset($_GET['amz_make']) ? sanitize_text_field(wp_unslash($_GET['amz_make'])) : '';
    ob_start(); ?>
    <div class="amz-parts-search"><form method="get" class="reg-panel" style="margin-bottom:22px"><label for="amz-part-search">Search by part, OE number or vehicle</label><div class="reg-row"><input class="reg-input" id="amz-part-search" name="amz_part_search" value="<?php echo esc_attr($term); ?>" placeholder="e.g. Golf Mk7 headlight or OE number"><button class="btn">Search parts</button></div></form>
    <?php
    $args = array('post_type'=>'product','post_status'=>'publish','posts_per_page'=>max(1,min(24,(int)$atts['limit'])),'s'=>$term);
    if ($make) $args['meta_query'] = array(array('key'=>'_amz_vehicle_fitment','value'=>$make,'compare'=>'LIKE'));
    $query = new WP_Query($args);
    if ($query->have_posts()) { echo '<div class="amz-search-results">'; while($query->have_posts()){ $query->the_post(); $p=wc_get_product(get_the_ID()); if(!$p)continue; echo '<article class="product-card"><a href="'.esc_url(get_permalink()).'"><div class="product-image">'.($p->get_image('woocommerce_thumbnail') ?: '<span class="placeholder-part">⚙</span>').'</div><div class="product-info"><span class="pill">'.esc_html(get_post_meta(get_the_ID(),'_amz_condition',true) ? 'Used part' : 'New / other').'</span><h3>'.esc_html(get_the_title()).'</h3><div class="product-meta">'.esc_html(get_post_meta(get_the_ID(),'_amz_vehicle_fitment',true)).'</div><div class="product-price">'.wp_kses_post($p->get_price_html()).'</div></div></a></article>'; } echo '</div>'; } else echo '<div class="amz-empty">No matching products found. Try a broader search or contact us to source the part.</div>';
    wp_reset_postdata(); ?></div><?php return ob_get_clean();
}
add_shortcode('amz_parts_search','amzcore_parts_search_shortcode');
function amzcore_price_comparison_shortcode($atts) {
    if (!class_exists('WooCommerce')) return '<div class="amz-empty">Install WooCommerce to show price comparisons.</div>';
    $atts=shortcode_atts(array('limit'=>6),$atts,'amz_price_comparison');
    $q=new WP_Query(array('post_type'=>'product','post_status'=>'publish','posts_per_page'=>max(1,min(20,(int)$atts['limit'])),'meta_query'=>array('relation'=>'OR',array('key'=>'_amz_used_market_price','compare'=>'EXISTS'),array('key'=>'_amz_new_market_price','compare'=>'EXISTS'))));
    ob_start(); if($q->have_posts()){echo '<div class="amz-search-results">';while($q->have_posts()){$q->the_post();$id=get_the_ID();$p=wc_get_product($id);$u=get_post_meta($id,'_amz_used_market_price',true);$n=get_post_meta($id,'_amz_new_market_price',true);echo '<article class="compare-card"><h3><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3><p>'.esc_html(get_post_meta($id,'_amz_vehicle_fitment',true)).'</p><div class="amz-price-row"><span>AMZ price</span><b>'.wp_kses_post($p->get_price_html()).'</b></div>';if($u!=='')echo '<div class="amz-price-row"><span>Used market reference</span><b>'.wp_kses_post(wc_price((float)$u)).'</b></div>';if($n!=='')echo '<div class="amz-price-row"><span>New price reference</span><b>'.wp_kses_post(wc_price((float)$n)).'</b></div>';echo '<p class="helper">Reference prices are manually maintained and require like-for-like verification.</p></article>';}echo '</div>';}else echo '<div class="amz-empty">Price comparisons will appear here after reference prices are added to products.</div>';wp_reset_postdata();return ob_get_clean();
}
add_shortcode('amz_price_comparison','amzcore_price_comparison_shortcode');
