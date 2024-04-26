<?php
add_action('wp_head', function () {
    global $wp_query;
    $cat = $wp_query->get_queried_object();
    ?>
    <style>
        <?php if (is_woocommerce_activated() && get_theme_mod('header_shop_bg_color')) { ?>
        .shop-page-title.category-page-title {
            background-color: <?php echo get_theme_mod('header_shop_bg_color') ?>;
        }

        <?php } ?>
        <?php if (is_product_taxonomy() &&  get_field( 'header_color_bg_category', $cat)) { ?>
        .shop-page-title.category-page-title {
            background-color: <?php echo get_field( 'header_color_bg_category', $cat) ?>;
        }

        <?php } ?>

        <?php if(get_theme_mod('site_width')) {
		$site_width = intval(get_theme_mod('site_width'));
        ?>

        div.row.row-small {
            max-width: <?php echo $site_width; ?>px
        }

        footer .is-border {
            max-width: <?php echo $site_width-20; ?>px
        }

        <?php
    };
        ?>
    </style>
    <?php
});