<?php
namespace KOP\Snippets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Limits image sizes and responsive image output to 2048px max width.
 */
class ImageSizes {

    private const MAX_WIDTH = 2048;

    public static function init(): void {
        add_filter( 'wp_calculate_image_sizes', [ self::class, 'limit_image_sizes' ], 10, 2 );
        add_filter( 'big_image_size_threshold', [ self::class, 'set_big_image_threshold' ] );
    }

    /**
     * @param string $sizes
     * @param array  $size
     */
    public static function limit_image_sizes( string $sizes, array $size ): string {
        return '(max-width: ' . self::MAX_WIDTH . 'px) 100vw, ' . self::MAX_WIDTH . 'px';
    }

    public static function set_big_image_threshold(): int {
        return self::MAX_WIDTH;
    }
}
