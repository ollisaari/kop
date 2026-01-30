<?php
/**
 * Block patterns functionality.
 *
 * @package KOP
 */

namespace KOP\Snippets;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Block patterns admin menu and shortcodes.
 */
class BlockPatterns {

    /**
     * Initialize hooks.
     */
    public static function init(): void {
        add_action( 'admin_menu', [ self::class, 'add_admin_menu' ] );
        add_shortcode( 'block_pattern', [ self::class, 'block_pattern_shortcode' ] );
    }

    /**
     * Add Block Patterns menu link to admin sidebar.
     */
    public static function add_admin_menu(): void {
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

    /**
     * Block pattern shortcode.
     *
     * Displays the content of a reusable block (wp_block post type).
     * Supports Polylang for translated blocks.
     *
     * Usage: [block_pattern id="123" lang="en" strip_tags="false"]
     *
     * @param array $atts Shortcode attributes.
     * @return string Block content or empty string.
     */
    public static function block_pattern_shortcode( $atts ): string {
        $attr = shortcode_atts( [
            'id'         => null,
            'lang'       => '',
            'strip_tags' => false,
        ], $atts, 'block_pattern' );

        if ( empty( $attr['id'] ) ) {
            return '';
        }

        // If Polylang is active, get the translated block
        $post_id = function_exists( 'pll_get_post' )
            ? pll_get_post( $attr['id'], $attr['lang'] )
            : $attr['id'];

        // Verify post type is wp_block
        if ( get_post_type( $post_id ) !== 'wp_block' ) {
            return '';
        }

        $content_post = get_post( $post_id );

        if ( ! isset( $content_post->post_content ) ) {
            return '';
        }

        // Handle strip_tags as string "false"
        if ( $attr['strip_tags'] === 'false' ) {
            $attr['strip_tags'] = false;
        }

        if ( $attr['strip_tags'] ) {
            $stripped_content = wp_strip_all_tags( $content_post->post_content, false );
            // Replace double newlines with single newline
            return str_replace( "\n\n", "\n", $stripped_content );
        }

        return apply_filters( 'the_content', $content_post->post_content );
    }
}
