<?php
/**
 * Admin menu customizations.
 *
 * @package KOP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Block Patterns menu link to admin sidebar.
 */
function kop_add_block_patterns_menu() {
    add_menu_page(
        'Block patterns',
        'Block patterns',
        'edit_posts',
        'edit.php?post_type=wp_block',
        '',
        'dashicons-screenoptions',
        80
    );
}
add_action( 'admin_menu', 'kop_add_block_patterns_menu' );
