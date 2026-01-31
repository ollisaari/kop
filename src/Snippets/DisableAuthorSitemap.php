<?php
namespace KOP\Snippets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Disables the author sitemap in Yoast SEO.
 */
class DisableAuthorSitemap {

    public static function init(): void {
        add_filter( 'wpseo_sitemap_exclude_author', '__return_true' );
    }
}
