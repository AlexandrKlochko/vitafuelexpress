<?php

$understrap_inc_dir = 'inc';

// Array of files to include.
$understrap_includes = [
    '/theme-enqueue.php', //Include scripts and styles
    '/woo.php', //Include scripts and styles
    '/custom-field.php', //
    '/wp-form.php', //
    '/themes-css.php', //
];

// Include files.
foreach ( $understrap_includes as $file ) {
    require_once get_theme_file_path( $understrap_inc_dir . $file );
}


add_filter( 'auto_plugin_update_send_email', '__return_false' );


