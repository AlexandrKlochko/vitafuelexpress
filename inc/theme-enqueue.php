<?php

function chunk_slider_scripts() {

    wp_enqueue_script( 'chunk.slider-js', get_stylesheet_directory_uri() . '/assets/js/chunk.slider.js', array( 'jquery' ),'',true );
}
add_action( 'wp_enqueue_scripts', 'chunk_slider_scripts' );
function enqueue_admin_scripts() {

    wp_enqueue_script( 'jquery-ui-datepicker-init',
        plugins_url( 'jquery-ui-datepicker-init.js', __FILE__ ),
        array( 'jquery', 'jquery-ui-datepicker' ),
        '1.00' );
}

function enqueue_admin_styles() {

    wp_enqueue_style( 'jquery-ui',
        'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
        array(),
        '1.00' );
}
function flatsome_child_scripts_styles() {
    global $wp_query;
    wp_enqueue_style('custom-css', get_stylesheet_directory_uri() . '/assets/css/custom.css', [], time());

    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/index.js', ['jquery'], time());

    wp_localize_script(
        'custom-js',
        'woo_loadmore_params',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'posts' => json_encode($wp_query->query_vars),
            'cur_page' => get_query_var('paged') ? get_query_var('paged') : 1,
            'max_page' => $wp_query->max_num_pages,
        )
    );

}
add_action( 'wp_enqueue_scripts', 'flatsome_child_scripts_styles', 999 );