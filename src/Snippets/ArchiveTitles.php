<?php
/**
 * Archive titles customization.
 *
 * @package KOP
 */

namespace KOP\Snippets;

use KOP\T;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customize archive page title prefixes.
 */
class ArchiveTitles {

    /**
     * Initialize hooks.
     */
    public static function init(): void {
        add_filter( 'get_the_archive_title_prefix', [ self::class, 'filter_archive_title_prefix' ] );
    }

    /**
     * Filter archive title prefixes.
     *
     * @param string $prefix The archive title prefix.
     * @return string Modified prefix.
     */
    public static function filter_archive_title_prefix( string $prefix ): string {
        if ( is_tag() ) {
            return T::__( 'topic' ) . ': ';
        }

        return $prefix;
    }
}
