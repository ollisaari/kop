<?php
namespace KOP\Snippets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Modifies Query Loop block with class "related-by-tags" to show posts
 * that share taxonomy terms with the current post.
 */
class RelatedPosts {

    private static bool $is_related_query = false;

    public static function init(): void {
        add_filter( 'pre_render_block', [ self::class, 'detect_related_block' ], 10, 2 );
        add_filter( 'query_loop_block_query_vars', [ self::class, 'filter_query' ], 10, 3 );
    }

    /**
     * Detect when we're rendering a query block with "related-by-tags" class.
     *
     * @param string|null $pre_render The pre-rendered content.
     * @param array       $parsed_block The block being rendered.
     * @return string|null
     */
    public static function detect_related_block( ?string $pre_render, array $parsed_block ): ?string {
        if ( $parsed_block['blockName'] !== 'core/query' ) {
            return $pre_render;
        }

        $class_name = $parsed_block['attrs']['className'] ?? '';
        self::$is_related_query = strpos( $class_name, 'related-by-tags' ) !== false;

        return $pre_render;
    }

    /**
     * Filter Query Loop block query to show related posts by shared taxonomy terms.
     *
     * @param array     $query Array of query args.
     * @param \WP_Block $block Block instance.
     * @param int       $page  Current page number.
     * @return array Modified query args.
     */
    public static function filter_query( array $query, \WP_Block $block, int $page ): array {
        if ( ! self::$is_related_query ) {
            return $query;
        }

        // Reset flag after use
        self::$is_related_query = false;

        // Only on single posts
        if ( ! is_singular( 'post' ) ) {
            return $query;
        }

        $current_post_id = get_queried_object_id();
        $tags = wp_get_post_tags( $current_post_id, [ 'fields' => 'ids' ] );

        if ( empty( $tags ) ) {
            return $query;
        }

        $query['tag__in'] = $tags;
        $query['post__not_in'] = [ $current_post_id ];

        return $query;
    }
}
