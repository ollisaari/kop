<?php
/**
 * Custom shortcodes.
 *
 * @package KOP
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
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
function kop_block_pattern_shortcode( $atts ) : string {
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
add_shortcode( 'block_pattern', 'kop_block_pattern_shortcode' );

/**
 * Related posts by tags shortcode.
 *
 * Displays up to 3 related posts that share tags with the current post.
 * Only works on single post pages.
 *
 * Usage: [related_posts_by_tags]
 *
 * @return string Related posts HTML or empty string.
 */
function kop_related_posts_by_tags_shortcode() : string {
    if ( ! is_singular( 'post' ) ) {
        return '';
    }

    $current_post_id = get_the_ID();
    $tags = wp_get_post_tags( $current_post_id, [ 'fields' => 'ids' ] );

    if ( empty( $tags ) ) {
        return '';
    }

    $related_posts = new WP_Query( [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => [ $current_post_id ],
        'tag__in'        => $tags,
    ] );

    if ( ! $related_posts->have_posts() ) {
        return '';
    }

    ob_start();
    echo '<div class="related-posts">';

    while ( $related_posts->have_posts() ) {
        $related_posts->the_post();
        ?>
        <div class="related-post-item">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </div>
        <?php
    }

    echo '</div>';
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'related_posts_by_tags', 'kop_related_posts_by_tags_shortcode' );
