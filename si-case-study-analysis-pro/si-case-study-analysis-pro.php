<?php
/**
 * Plugin Name: SI Case Study Analysis Pro
 * Plugin URI:  https://example.com/si-case-study-analysis-pro
 * Description: A structured case-study intelligence and publishing system for WordPress.
 * Version:     1.0.0
 * Author:      SI Framework
 * Author URI:  https://example.com
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: si-csap
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define plugin constants.
 */
define( 'SI_CSAP_VERSION', '1.0.0' );
define( 'SI_CSAP_PLUGIN_FILE', __FILE__ );
define( 'SI_CSAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SI_CSAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SI_CSAP_TEXT_DOMAIN', 'si-csap' );
define( 'SI_CSAP_MIN_PHP_VERSION', '7.4' );
define( 'SI_CSAP_MIN_WP_VERSION', '5.8' );

/**
 * Load the manifest file.
 * Contains plugin metadata, module registry, and feature flags.
 */
require_once SI_CSAP_PLUGIN_DIR . 'manifest.php';

/**
 * Load the Bootstrap orchestrator.
 * This handles the dependency injection container, autoloader, and application boot.
 */
require_once SI_CSAP_PLUGIN_DIR . 'includes/SI Framework/Core/Bootstrap.php';

/**
 * Plugin activation hook.
 *
 * @return void
 */
function si_csap_activate(): void {
    // Flush rewrite rules to ensure custom endpoints are registered.
    flush_rewrite_rules();
    
    // Set a transient to show a welcome/setup notice on activation.
    set_transient( 'si_csap_activation_notice', true, 30 );
}
register_activation_hook( __FILE__, 'si_csap_activate' );

/**
 * Plugin deactivation hook.
 *
 * @return void
 */
function si_csap_deactivate(): void {
    // Flush rewrite rules on deactivation.
    flush_rewrite_rules();
    
    // Clear any scheduled cron jobs if added later.
    // wp_clear_scheduled_hook( 'si_csap_daily_cleanup' );
}
register_deactivation_hook( __FILE__, 'si_csap_deactivate' );

/**
 * Initialize the application.
 * Hooked to 'plugins_loaded' to ensure all other plugins are loaded first.
 *
 * @return void
 */
function si_csap_init(): void {
    // Boot the SI Framework Application.
    \SICSAP\Core\Bootstrap::boot();
}
add_action( 'plugins_loaded', 'si_csap_init' );