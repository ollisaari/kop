<?php
/**
 * Short helper class for translations.
 *
 * @package KOP
 */

namespace KOP;

use KOP\Snippets\Translations;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shorthand helper for accessing translated strings.
 */
class T {

    /**
     * Get a translated string.
     *
     * @param string $key The string key.
     * @return string Translated string.
     */
    public static function __( string $key ): string {
        return Translations::get( $key );
    }

    /**
     * Echo a translated string (escaped).
     *
     * @param string $key The string key.
     */
    public static function _e( string $key ): void {
        Translations::e( $key );
    }

    /**
     * Get a translated string with fallback default.
     *
     * @param string $key     The string key.
     * @param string $default Default value if key not found.
     * @return string Translated string or default.
     */
    public static function _x( string $key, string $default ): string {
        return Translations::get( $key, $default );
    }
}
