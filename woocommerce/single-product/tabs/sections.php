<?php
/**
 * Single Product tabs
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          2.0.0
 * @flatsome-version 3.16.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($product_tabs)) : ?>
    <div class="product-page-sections">
        <div class="row">
            <?php if (isset($product_tabs['description'])): ?>
                <div class="product-section large-7 col">
                    <div class="row">
                        <div class="col pb-0 mb-0">
                            <div class="panel entry-content">
                                <?php
                                if (isset($product_tabs['description']['callback'])) {
                                    call_user_func($product_tabs['description']['callback'], 'description', $product_tabs['description']);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php unset($product_tabs['description']) ?>
            <?php endif; ?>
            <div class="product-section large-5 col product-section-boxed">
                <div class="row">
                    <div class="col pb-0 mb-0">
                        <?php foreach ($product_tabs as $key => $product_tab) : ?>
                            <div class="panel entry-content " >
                                <h5 class="uppercase"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ); ?></h5>
                                <?php
                                if (isset($product_tab['callback'])) {
                                    call_user_func($product_tab['callback'], $key, $product_tab);
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
