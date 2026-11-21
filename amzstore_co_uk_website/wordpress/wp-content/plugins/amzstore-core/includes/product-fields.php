<?php
if (!defined('ABSPATH')) { exit; }
function amzcore_add_product_fields() {
    if (!function_exists('woocommerce_wp_select')) return;
    echo '<div class="options_group"><h4 style="padding:0 12px">AMZ Store part details</h4>';
    woocommerce_wp_select(array('id'=>'_amz_stock_source','label'=>'Stock source','options'=>array('owned'=>'AMZ Store owned stock','supplier'=>'External UK supplier'),'desc_tip'=>true,'description'=>'Identify whether this item is physically held by AMZ Store or supplied by a partner.'));
    woocommerce_wp_select(array('id'=>'_amz_condition','label'=>'Used-part condition','options'=>array(''=>'Not applicable / new','A'=>'Grade A – excellent used','B'=>'Grade B – good used','C'=>'Grade C – fair used'),'desc_tip'=>true,'description'=>'Only select a grade for used parts. Describe all material defects in the product description.'));
    woocommerce_wp_text_input(array('id'=>'_amz_part_number','label'=>'OE / part number','desc_tip'=>true,'description'=>'Original equipment or manufacturer part reference.'));
    woocommerce_wp_text_input(array('id'=>'_amz_vehicle_fitment','label'=>'Vehicle fitment','placeholder'=>'e.g. Ford Focus Mk3, 2011–2018, 1.6 TDCi'));
    woocommerce_wp_text_input(array('id'=>'_amz_engine_code','label'=>'Engine / trim code'));
    woocommerce_wp_select(array('id'=>'_amz_side','label'=>'Part side','options'=>array(''=>'Not applicable','left'=>'Left','right'=>'Right','front'=>'Front','rear'=>'Rear','pair'=>'Pair')));
    woocommerce_wp_select(array('id'=>'_amz_test_status','label'=>'Inspection status','options'=>array('not-tested'=>'Not tested','tested'=>'Tested','visual-only'=>'Visual inspection only'),'desc_tip'=>true,'description'=>'Do not describe safety-critical components as tested unless appropriate checks were completed.'));
    woocommerce_wp_textarea_input(array('id'=>'_amz_defects','label'=>'Known defects / notes','description'=>'Disclose scratches, broken tabs, missing fixings or other known issues.'));
    woocommerce_wp_text_input(array('id'=>'_amz_supplier_name','label'=>'Supplier name'));
    woocommerce_wp_text_input(array('id'=>'_amz_supplier_sku','label'=>'Supplier reference'));
    woocommerce_wp_text_input(array('id'=>'_amz_supplier_cost','label'=>'Supplier / acquisition cost (£)','type'=>'number','custom_attributes'=>array('step'=>'0.01','min'=>'0')));
    woocommerce_wp_text_input(array('id'=>'_amz_used_market_price','label'=>'UK used-market reference (£)','type'=>'number','custom_attributes'=>array('step'=>'0.01','min'=>'0')));
    woocommerce_wp_text_input(array('id'=>'_amz_new_market_price','label'=>'Comparable new price (£)','type'=>'number','custom_attributes'=>array('step'=>'0.01','min'=>'0')));
    woocommerce_wp_text_input(array('id'=>'_amz_price_checked','label'=>'Price checked date','placeholder'=>'YYYY-MM-DD'));
    echo '</div>';
}
add_action('woocommerce_product_options_inventory_product_data','amzcore_add_product_fields');
function amzcore_save_product_fields($product) {
    $fields = array('_amz_stock_source','_amz_condition','_amz_part_number','_amz_vehicle_fitment','_amz_engine_code','_amz_side','_amz_test_status','_amz_defects','_amz_supplier_name','_amz_supplier_sku','_amz_supplier_cost','_amz_used_market_price','_amz_new_market_price','_amz_price_checked');
    foreach ($fields as $field) {
        if (!isset($_POST[$field])) { $product->delete_meta_data($field); continue; }
        $raw = wp_unslash($_POST[$field]);
        if (in_array($field,array('_amz_supplier_cost','_amz_used_market_price','_amz_new_market_price'),true)) $value = wc_format_decimal($raw);
        elseif ($field === '_amz_defects') $value = sanitize_textarea_field($raw);
        else $value = sanitize_text_field($raw);
        $product->update_meta_data($field,$value);
    }
}
add_action('woocommerce_admin_process_product_object','amzcore_save_product_fields');
function amzcore_display_product_details() {
    global $product;
    if (!$product) return;
    $id = $product->get_id();
    $source = get_post_meta($id,'_amz_stock_source',true);
    $condition = get_post_meta($id,'_amz_condition',true);
    $part = get_post_meta($id,'_amz_part_number',true);
    $fitment = get_post_meta($id,'_amz_vehicle_fitment',true);
    $engine = get_post_meta($id,'_amz_engine_code',true);
    $side = get_post_meta($id,'_amz_side',true);
    $tested = get_post_meta($id,'_amz_test_status',true);
    $defects = get_post_meta($id,'_amz_defects',true);
    $supplier = get_post_meta($id,'_amz_supplier_name',true);
    $rows = array();
    if ($source) $rows[] = array('Stock source',$source === 'owned' ? 'AMZ Store stock' : 'Partner supplier stock');
    if ($condition) $rows[] = array('Condition',array('A'=>'Grade A – excellent used','B'=>'Grade B – good used','C'=>'Grade C – fair used')[$condition] ?? $condition);
    if ($part) $rows[] = array('OE / part number',$part);
    if ($fitment) $rows[] = array('Vehicle fitment',$fitment);
    if ($engine) $rows[] = array('Engine / trim',$engine);
    if ($side) $rows[] = array('Position',ucfirst($side));
    if ($tested) $rows[] = array('Inspection',array('tested'=>'Tested','visual-only'=>'Visual inspection only','not-tested'=>'Not tested')[$tested] ?? $tested);
    if ($supplier && $source === 'supplier') $rows[] = array('Supplier',$supplier);
    if (!$rows && !$defects) return;
    echo '<section class="amz-product-details"><h3>Part details & compatibility</h3><div class="amz-spec-grid">';
    foreach ($rows as $row) echo '<div class="amz-spec"><b>'.esc_html($row[0]).':</b> '.esc_html($row[1]).'</div>';
    echo '</div>';
    if ($defects) echo '<p><b>Known defects / notes:</b> '.esc_html($defects).'</p>';
    echo '<p class="helper">Please confirm the part number, vehicle variant and connector/specification before ordering. Registration lookup is not enabled until a vehicle-data provider is connected.</p></section>';
}
add_action('woocommerce_single_product_summary','amzcore_display_product_details',25);
function amzcore_display_price_comparison() {
    global $product;
    if (!$product) return;
    $id = $product->get_id();
    $used = get_post_meta($id,'_amz_used_market_price',true);
    $new = get_post_meta($id,'_amz_new_market_price',true);
    $checked = get_post_meta($id,'_amz_price_checked',true);
    if ($used === '' && $new === '') return;
    echo '<section class="amz-price-compare"><h3>Price comparison references</h3>';
    if ($used !== '') echo '<div class="amz-price-row"><span>UK used-market reference</span><b>'.wp_kses_post(wc_price((float)$used)).'</b></div>';
    if ($new !== '') echo '<div class="amz-price-row"><span>Comparable new-part reference</span><b>'.wp_kses_post(wc_price((float)$new)).'</b></div>';
    if ($checked) echo '<p class="helper">Reference last checked: '.esc_html($checked).'. These are administrator-entered reference prices, not live verified offers.</p>';
    else echo '<p class="helper">Reference prices are entered by the store administrator and are not live verified offers.</p>';
    echo '</section>';
}
add_action('woocommerce_single_product_summary','amzcore_display_price_comparison',36);
