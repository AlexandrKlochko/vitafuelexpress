<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          3.3.1
 * @flatsome-version 3.16.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
if ($total <= 1) {
    return;
}
?>
<div class="container">
    <div class="btn__wrapper">
        <a href="#" class="btn btn__primary button" id="load-more">Load more</a>
    </div>

</div>
