<?php
namespace KOP\Snippets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Related posts by tags shortcode.
 */
class RelatedPosts {

    public static function init(): void {
        add_shortcode( 'related_posts_by_tags', [ self::class, 'shortcode' ] );
    }

    /**
     * Displays up to 3 related posts that share tags with the current post.
     * Only works on single post pages.
     *
     * Usage: [related_posts_by_tags]
     */
    public static function shortcode(): string {
        
        if ( ! is_singular( 'post' ) ) {
            return '';
        }

        $current_post_id = get_the_ID();
        $tags = wp_get_post_tags( $current_post_id, [ 'fields' => 'ids' ] );

        if ( empty( $tags ) ) {
            return '';
        }

        $related_posts = new \WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post__not_in'   => [ $current_post_id ],
            'tag__in'        => $tags,
        ] );

        ob_start();
        if ( $related_posts->have_posts() ) {
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
        }

        return ob_get_clean();
    }
}
