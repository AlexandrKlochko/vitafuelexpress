<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see              https://docs.woocommerce.com/document/template-structure/
 * @package          WooCommerce/Templates
 * @version          3.6.0
 * @flatsome-version 3.16.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || false === wc_get_loop_product_visibility($product->get_id()) || !$product->is_visible()) {
    return;
}

// Check stock status.
$out_of_stock = !$product->is_in_stock();

// Extra post classes.
$classes = array();
$classes[] = 'product-small';
$classes[] = 'col';
$classes[] = 'has-hover';

if ($out_of_stock) $classes[] = 'out-of-stock';

?>
<div <?php wc_product_class($classes, $product); ?>>
    <div class="col-inner product-default">
        <?php do_action('woocommerce_before_shop_loop_item'); ?>

        <div class="product-small box <?php echo flatsome_product_box_class(); ?>">
            <div class="product-top">
                <div class="product-top-status">
                    <?php
                    $stock_status = $product->get_stock_status();
                    if ('instock' === $stock_status) {
                        echo '<svg width="10" height="7" viewBox="0 0 10 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M9 0.5L3.5 6L1 3.5" stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>' . __('In Stock');
                    } elseif ('onbackorder' === $stock_status) {
                        echo __('Out of stock');
                    } ?>
                </div>
                <div>
                    <div class="image-tools is-small top right">
                        <?php do_action('flatsome_product_box_tools_top'); ?>
                    </div>
                </div>
            </div>



            <div class="box-image">

                <div class="<?php echo flatsome_product_box_image_class(); ?>">
                    <a href="<?php echo get_the_permalink(); ?>"
                       aria-label="<?php echo esc_attr($product->get_title()); ?>">
                        <?php
                        /**
                         *
                         * @hooked woocommerce_get_alt_product_thumbnail - 11
                         * @hooked woocommerce_template_loop_product_thumbnail - 10
                         */
                        do_action('flatsome_woocommerce_shop_loop_images');
                        ?>
                    </a>
                </div>

                <div class="image-tools is-small hide-for-small bottom left show-on-hover">
                    <?php do_action('flatsome_product_box_tools_bottom'); ?>
                </div>

                <?php
                $wc_product_attribute = $product->get_attributes();

                if (isset($wc_product_attribute['pa_ingredients'])) {
                    $attr_id = $wc_product_attribute['pa_ingredients']->get_terms();
                    echo '  <div class="pa_ingredients">';
                    foreach ($wc_product_attribute['pa_ingredients']->get_terms() as $key=> $ingredient) {
                        $icon = get_field('icon', $ingredient);
                        if ($icon) {
                            echo wp_get_attachment_image($icon['id'], [30, 30]);
                        }
                        if($key>3) break;
                    }
                    echo '    </div>';
                };
                if (isset($wc_product_attribute['pa_directions-dosage'])) {
                    $attr_id = $wc_product_attribute['pa_directions-dosage']->get_terms();
                    echo '  <div class="pa_directions-dosage">';
                    foreach ($wc_product_attribute['pa_directions-dosage']->get_terms() as $key=> $ingredient) {
                        $icon = get_field('icon', $ingredient);
                        if ($icon) {
                            echo wp_get_attachment_image($icon['id'], [30, 30]);
                        }
                        if($key>3) break;
                    }
                    echo '    </div>';
                };
                if (isset($wc_product_attribute['pa_supplement-facts'])) {
                    $attr_id = $wc_product_attribute['pa_supplement-facts']->get_terms();
                    echo '  <div class="pa_supplement-facts">';
                    foreach ($wc_product_attribute['pa_supplement-facts']->get_terms() as $key=> $ingredient) {
                        $icon = get_field('icon', $ingredient);
                        if ($icon) {
                            echo wp_get_attachment_image($icon['id'], [30, 30]);
                        }
                        if($key>3) break;
                    }
                    echo '    </div>';
                };

                ?>

            </div>

            <div class="box-text <?php echo flatsome_product_box_text_class(); ?>">
                <?php
                do_action('woocommerce_before_shop_loop_item_title');

                echo '<div class="title-wrapper">';
                do_action('woocommerce_shop_loop_item_title');

                echo '<div class="excerpt-wrapper">';

                echo get_the_excerpt($product->get_id());

                echo '</div>';

                echo '</div>';


                echo '<div class="price-cart-wrapper">';
                echo '<div class="price-wrapper">';

                echo '<div class="price-total">';
                echo $product->get_price_html();
                echo '</div>';


                $save = $product->get_regular_price() - $product->get_price();
                $save = number_format((float)$save, 0, '.', '');
                if ($save) {
                    echo '<div class="price-sale">';
                    echo '<span class="save">' . __('Save: ') . get_woocommerce_currency_symbol() . $save . '</span>';
                    echo '</div>';
                }


                echo '</div>';

                echo '<div class="add-cart__wrap">';

                do_action('woocommerce_simple_add_to_cart');

                echo '</div>';
                echo '</div>';


                do_action('flatsome_product_box_after');

                ?>
            </div>
        </div>
        <?php do_action('woocommerce_after_shop_loop_item'); ?>
    </div>
</div><?php /* empty PHP to avoid whitespace */ ?>
