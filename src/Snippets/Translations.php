<?php
/**
 * Translations snippet for Polylang string registration.
 *
 * @package KOP
 */

namespace KOP\Snippets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles registration and retrieval of translatable strings via Polylang.
 */
class Translations {

    /**
     * Group name for Polylang Strings Translation.
     */
    public const GROUP = 'KOP';

    /**
     * All translatable strings.
     * Key => Source string (English).
     */
    private const STRINGS = [
        'topic'     => 'Topic',
    ];

    /**
     * Initialize the snippet.
     */
    public static function init(): void {
        add_action( 'init', [ self::class, 'register_strings' ] );
    }

    /**
     * Register all strings with Polylang.
     */
    public static function register_strings(): void {
        if ( ! function_exists( 'pll_register_string' ) ) {
            return;
        }

        foreach ( self::STRINGS as $key => $string ) {
            pll_register_string( $key, $string, self::GROUP, false );
        }
    }

    /**
     * Get a translated string.
     *
     * @param string $key     The string key.
     * @param string $default Default value if key not found.
     * @return string Translated string or default.
     */
    public static function get( string $key, string $default = '' ): string {
        $source = self::STRINGS[ $key ] ?? $default;

        if ( empty( $source ) ) {
            return $default;
        }

        return function_exists( 'pll__' ) ? pll__( $source ) : $source;
    }

    /**
     * Echo a translated string (escaped).
     *
     * @param string $key     The string key.
     * @param string $default Default value if key not found.
     */
    public static function e( string $key, string $default = '' ): void {
        echo esc_html( self::get( $key, $default ) );
    }
}
