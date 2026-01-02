<?php
/**
 * Plugin Name: KOP
 * Plugin URI: https://github.com/ollisaari/kop
 * Description: Custom functionality for KOP WordPress site.
 * Version: 1.0.0
 * Author: Olli Saari
 * Author URI: https://github.com/ollisaari
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kop
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants
define( 'KOP_VERSION', '1.0.0' );
define( 'KOP_PLUGIN_FILE', __FILE__ );
define( 'KOP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Load Composer autoloader
if ( file_exists( KOP_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require KOP_PLUGIN_DIR . 'vendor/autoload.php';
}

// Initialize plugin update checker
if ( class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
    $kop_update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        'https://github.com/ollisaari/kop/',
        __FILE__,
        'kop'
    );

    // Use GitHub releases
    $kop_update_checker->getVcsApi()->enableReleaseAssets();
}

// Load plugin features
require_once KOP_PLUGIN_DIR . 'includes/admin-menu.php';
require_once KOP_PLUGIN_DIR . 'includes/shortcodes.php';
