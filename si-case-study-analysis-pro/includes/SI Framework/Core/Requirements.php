<?php
/**
 * Requirements Checker
 *
 * Verifies PHP/WP version and required extensions before anything else boots.
 * Halts boot with an admin notice on failure.
 *
 * @package SICSAP\Core
 */

declare(strict_types=1);

namespace SICSAP\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Class Requirements
 *
 * Handles all environment requirement checks for the plugin.
 */
class Requirements {

    /**
     * List of error messages if any check fails.
     *
     * @var string[]
     */
    private array $errors = [];

    /**
     * Constructor.
     *
     * @param string   $min_php             Minimum required PHP version.
     * @param string   $min_wp              Minimum required WordPress version.
     * @param string[] $required_extensions Array of required PHP extensions.
     */
    public function __construct(
        private string $min_php,
        private string $min_wp,
        private array $required_extensions = []
    ) {
    }

    /**
     * Run all requirement checks.
     *
     * @return bool True if all requirements are met, false otherwise.
     */
    public function check(): bool {
        $this->check_php_version();
        $this->check_wp_version();
        $this->check_extensions();

        if ( ! empty( $this->errors ) ) {
            $this->halt_boot();
            return false;
        }

        return true;
    }

    /**
     * Get the list of errors.
     *
     * @return string[]
     */
    public function get_errors(): array {
        return $this->errors;
    }

    /**
     * Check if the current PHP version meets the minimum requirement.
     *
     * @return void
     */
    private function check_php_version(): void {
        if ( version_compare( PHP_VERSION, $this->min_php, '<' ) ) {
            $this->errors[] = sprintf(
                /* translators: 1: Required PHP version, 2: Current PHP version. */
                __( 'SI Case Study Analysis Pro requires PHP version %1$s or higher. You are running version %2$s.', 'si-csap' ),
                $this->min_php,
                PHP_VERSION
            );
        }
    }

    /**
     * Check if the current WordPress version meets the minimum requirement.
     *
     * @return void
     */
    private function check_wp_version(): void {
        global $wp_version;

        if ( version_compare( $wp_version, $this->min_wp, '<' ) ) {
            $this->errors[] = sprintf(
                /* translators: 1: Required WordPress version, 2: Current WordPress version. */
                __( 'SI Case Study Analysis Pro requires WordPress version %1$s or higher. You are running version %2$s.', 'si-csap' ),
                $this->min_wp,
                $wp_version
            );
        }
    }

    /**
     * Check if all required PHP extensions are loaded.
     *
     * @return void
     */
    private function check_extensions(): void {
        foreach ( $this->required_extensions as $extension ) {
            if ( ! extension_loaded( $extension ) ) {
                $this->errors[] = sprintf(
                    /* translators: %s: Required PHP extension name. */
                    __( 'SI Case Study Analysis Pro requires the PHP extension "%s" to be installed and enabled.', 'si-csap' ),
                    esc_html( $extension )
                );
            }
        }
    }

    /**
     * Halt the plugin boot process and display admin notices.
     *
     * @return void
     */
    private function halt_boot(): void {
        // Register the admin notice to display the errors.
        add_action( 'admin_notices', [ $this, 'render_notices' ] );

        // Deactivate the plugin to prevent fatal errors on the frontend.
        // We hook into admin_init to ensure WP functions are fully loaded.
        add_action( 'admin_init', [ $this, 'deactivate_plugin' ] );
    }

    /**
     * Render the error notices in the WordPress admin.
     *
     * @return void
     */
    public function render_notices(): void {
        if ( empty( $this->errors ) ) {
            return;
        }

        echo '<div class="notice notice-error">';
        echo '<p><strong>' . esc_html__( 'SI Case Study Analysis Pro Error:', 'si-csap' ) . '</strong></p>';
        echo '<ul style="list-style: disc; margin-left: 20px;">';
        foreach ( $this->errors as $error ) {
            echo '<li>' . esc_html( $error ) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    /**
     * Deactivate the plugin if requirements are not met.
     *
     * @return void
     */
    public function deactivate_plugin(): void {
        if ( ! function_exists( 'deactivate_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if ( function_exists( 'deactivate_plugins' ) && defined( 'SI_CSAP_PLUGIN_FILE' ) ) {
            deactivate_plugins( plugin_basename( SI_CSAP_PLUGIN_FILE ) );
            
            // Show a transient message that the plugin was deactivated.
            if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                unset( $_GET['activate'] );
            }
        }
    }
}