<?php
/**
 * Plugin Name: SI Case Study Analysis Pro
 * Plugin URI: https://si-case-study.com/
 * Description: Professional case study analysis, argumentation, and reporting toolkit for WordPress.
 * Version: 1.0.0
 * Author: SI Case Study
 * Author URI: https://si-case-study.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: si-case-study-analysis-pro
 * Domain Path: /languages
 * Requires at least: 6.4
 * Requires PHP: 8.0
 *
 * @package SI_CSAP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('SI_CAP_VERSION')) {
    define('SI_CAP_VERSION', '1.0.0');
}
if (!defined('SI_CAP_PLUGIN_FILE')) {
    define('SI_CAP_PLUGIN_FILE', __FILE__);
}
if (!defined('SI_CAP_PLUGIN_DIR')) {
    define('SI_CAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('SI_CAP_PLUGIN_URL')) {
    define('SI_CAP_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('SI_CAP_PLUGIN_BASENAME')) {
    define('SI_CAP_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

require_once SI_CAP_PLUGIN_DIR . 'includes/SI Framework/Core/Loader.php';
spl_autoload_register(['SI_CSAP\Core\Loader', 'autoload']);

require_once SI_CAP_PLUGIN_DIR . 'includes/SI Framework/Core/Bootstrap.php';
register_activation_hook(__FILE__, ['SI_CSAP\Core\Bootstrap', 'activate']);
register_deactivation_hook(__FILE__, ['SI_CSAP\Core\Bootstrap', 'deactivate']);

add_action('plugins_loaded', function() {
    if (!SI_CSAP\Core\Requirements::check()) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>' . __('SI Case Study Analysis Pro requirements not met. Please upgrade PHP to 8.0+ and WordPress to 6.4+.', 'si-case-study-analysis-pro') . '</p></div>';
        });
        return;
    }
    
    $app = SI_CSAP\Core\Application::get_instance();
    $app->run();
}, 1);
