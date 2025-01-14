<?php

function get_icon_url($country)
{
    return 'https://exactly.com';
}
add_action('wp_footer', 'single_add_to_cart_event_text_replacement');
function single_add_to_cart_event_text_replacement()
{
    global $product;

    if (!is_product()) return; // Only single product pages
    if ($product->is_type('variable')) return; // Not variable products
    ?>
    <script type="text/javascript">
        (function ($) {
            $('button.single_add_to_cart_button, .add_to_cart_button').click(function () {
                $(this).text('<?php _e("View cart", "woocommerce"); ?>');
            });
        })(jQuery);
    </script>
    <?php
}

remove_action('flatsome_product_box_actions', 'flatsome_lightbox_button', 50);

function add_cart_flatsome()
{
    if (get_theme_mod('disable_quick_view', 0)) {
        return;
    }

    global $product;

    echo '  <a rel="nofollow" href="/?add-to-cart=' . $product->get_id() . '" data-quantity="1" data-product_id="' . $product->get_id() . '" data-product_sku="" class="add_to_cart_button ajax_add_to_cart">' . __('Add to cart', 'flatsome') . '</a>';
}

add_action('flatsome_product_box_actions', 'add_cart_flatsome', 50);


add_filter('woocommerce_registration_redirect', 'custom_redirection_after_registration', 10, 1);
function custom_redirection_after_registration($redirection_url)
{
    // Change the redirection Url
    $redirection_url = get_permalink(wc_get_page_id('myaccount'));

    return $redirection_url;
}


add_filter('woocommerce_product_description_tab_title', 'rename_description_product_tab_label');
function rename_description_product_tab_label()
{
    return 'PRODUCT OVERVIEW';
}

add_filter('woocommerce_order_number', function ($default_order_number, \WC_Order $order) {

    $order_number = $order->get_meta('_order_change');
    $url_invoice = ['download_invoice', 'print_invoice'];
    if (!empty($order_number) && in_array($_GET["type"], $url_invoice)) {
        return $order_number;
    }

    return $default_order_number;
}, 10, 2);


add_filter('wf_pklist_order_additional_item_meta', 'wf_pklist_add_order_meta', 10, 3);
function wf_pklist_add_order_meta($order_item_meta_data, $template_type, $order)
{

    $order_id = $order->get_id();
    $meta = get_post_meta($order_id, '_payment_change', true);
    $order_item_meta_data = $meta;
    return 'Payments: <strong>' . $order_item_meta_data . '</strong>';
}


add_filter('woocommerce_available_payment_gateways', 'woocommerce_available_payment_gateways');
function woocommerce_available_payment_gateways($available_gateways)
{
    if (!is_checkout()) return $available_gateways;  // stop doing anything if we're not on checkout page.
    if (array_key_exists('exactly', $available_gateways)) {
        $available_gateways['exactly']->order_button_text = __('Proceed', 'woocommerce');
    }
    return $available_gateways;
}


add_filter('woocommerce_registration_error_email_exists', function ($html) {
    $url = wc_get_page_permalink('myaccount');
    $url = add_query_arg('redirect_checkout', 1, $url);
    $html = str_replace('An account is already registered with your email address. ', 'An account is already registered with your email address. <a href="' . $url . '">Please log in.</a>', $html);
    return $html;
});


add_filter('woocommerce_gateway_icon', 'change_woocommerce_gateway_icon', 10, 2);
function change_woocommerce_gateway_icon($icon, $id)
{
    if ($id === 'exactly') {
        $base_country = WC()->countries->get_base_country();

        $icon_html = sprintf('<a href="%1$s" class="about_exactly" onclick="javascript:window.open(\'%1$s\',\'WIOP\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__('', 'exactly_payment') . '</a>', esc_url(get_icon_url($base_country)));

        $icon_html .= '<div style="text-align:center;"><img style="max-width:300px;width:100%;max-height:none;float:none;margin:0 auto;" src="/wp-content/uploads/2023/06/check.png" alt="' . esc_attr__('Exactly', 'woocommerce') . '" /></div>';

        return $icon_html;
    } else {
        return $icon;
    }
}


function my_custom_show_sale_price_at_cart($old_display, $cart_item, $cart_item_key)
{

    /** @var WC_Product $product */
    $product = $cart_item['data'];

    if ($product) {
        return $product->get_price_html();
    }

    return $old_display;

}

add_filter('woocommerce_cart_item_price', 'my_custom_show_sale_price_at_cart', 10, 3);

function filter_woocommerce_cart_subtotal($subtotal, $compound, $cart)
{
    $subtotal = 0;
    foreach (WC()->cart->get_cart() as $key => $cart_item) {
        $subtotal += $cart_item['data']->get_regular_price() * $cart_item['quantity'];
    }
    $subtotal = wc_price($subtotal);
    return $subtotal;
}

add_filter('woocommerce_cart_subtotal', 'filter_woocommerce_cart_subtotal', 10, 3);


add_action('woocommerce_cart_totals_before_order_total', 'bbloomer_show_total_discount_cart_checkout', 9999);
add_action('woocommerce_review_order_before_order_total', 'bbloomer_show_total_discount_cart_checkout', 9999);


function bbloomer_show_total_discount_cart_checkout()
{
    $discount_total = 0;
    foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
        $product = $values['data'];
        if ($product->is_on_sale()) {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $discount = ((float)$regular_price - (float)$sale_price) * (int)$values['quantity'];
            $discount_total += $discount;
        }
    }
    if ($discount_total > 0) {
        echo '<tr class="discount-total"><th>Discounts</th><td data-title="Discounts">' . wc_price($discount_total + WC()->cart->get_discount_total()) . '</td></tr>';
    }
}


//company_email invoice
add_action('admin_menu', 'company_email_add_option_to_general');
function company_email_add_option_to_general()
{
    $option_name = 'company_email';
    register_setting('general', $option_name);
    add_settings_field(
        $option_name,
        'Сompany email',
        'company_email_option_callback',
        'general',
        'default',
        array(
            'label_for' => $option_name,
            'name' => $option_name
        )
    );
}




add_filter('auto_plugin_update_send_email', '__return_false');


add_filter('woocommerce_registration_error_email_exists', function ($html) {
    $url = wc_get_page_permalink('myaccount');
    $url = add_query_arg('redirect_checkout', 1, $url);
    $html = str_replace('An account is already registered with your email address. ', 'An account is already registered with your email address. <a href="' . $url . '">Please log in.</a>', $html);
    return $html;
});

add_action('woocommerce_archive_description', function () {
    ?>
    <div class="shop-container__top">
        <?php
        do_action('flatsome-child-woocommerce_archive_description');
        ?>
    </div>
    <?php
});
add_action('flatsome-child-woocommerce_archive_description', 'wc_setup_loop');

add_action('flatsome-child-woocommerce_archive_description', 'woocommerce_result_count', 20);
add_action('flatsome-child-woocommerce_archive_description', 'woocommerce_catalog_ordering', 30);


add_action('woocommerce_category_before_main_content', function () {
    $taxonomy = "product_cat";
    $terms = get_terms($taxonomy, array('hide_empty' => true,)); //Exclude Specific Category by ID

    ?>
    <div class="row"><?php
        foreach ($terms as $term) {
            $thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_image($thumbnail_id); ?>


            <div class="col-term col">
                <a href="<?php echo get_term_link($term) ?>">
                    <?php

                    echo $image?:'<span class="rb"></span>';
                    echo '<p class="term-title">' . $term->name . '</p>';
                    ?>
                </a>
            </div>

            <?php
        } ?>
    </div>
    <?php
});
add_filter('woocommerce_catalog_orderby', function ($array) {
    return array(
        'popularity' => __('Most Popular', 'woocommerce'),
        'rating' => __('Sort by average rating', 'woocommerce'),
        'title_asc' => __('A-Z', 'woocommerce'),
        'title_desc' => __('Z-A', 'woocommerce'),
        'date' => __('Newest', 'woocommerce'),

    );
}, 99);

add_filter('woocommerce_get_catalog_ordering_args', 'custom_get_catalog_ordering_args');
function custom_get_catalog_ordering_args($args)
{
    if (isset($_GET['orderby'])) {
        // Sort by "menu_order" DESC (the default option)
        if ('title_desc' === $_GET['orderby']) {
            $args = array('orderby' => 'title', 'order' => 'DESC');
        } // Sort by "menu_order" ASC
        elseif ('title_asc' == $_GET['orderby']) {
            $args = array('orderby' => 'title', 'order' => 'ASC');
        }
    }
    return $args;
}

remove_action( 'flatsome_category_title', 'flatsome_add_category_filter_button', 999 );
