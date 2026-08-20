<?php
/**
 * Shared Rendering Helper
 *
 * Provides secure template loading and standardized escaping helpers
 * for admin screens and Gutenberg dynamic blocks.
 *
 * @package SICSAP\Engine
 */

declare(strict_types=1);

namespace SICSAP\Engine;

defined( 'ABSPATH' ) || exit;

/**
 * Class Renderer
 *
 * Handles template inclusion and output escaping securely.
 */
class Renderer {

    /**
     * The base directory for templates.
     *
     * @var string
     */
    private string $template_dir;

    /**
     * Constructor.
     * Initializes the template directory path.
     */
    public function __construct() {
        $this->template_dir = SI_CSAP_PLUGIN_DIR . 'templates/';
    }

    /**
     * Render a template file with optional arguments.
     *
     * @param string $template_name The template file name (relative to the templates/ directory).
     * @param array  $args          Optional. Associative array of arguments to extract into the template scope.
     *
     * @return void
     */
    public function render( string $template_name, array $args = [] ): void {
        $template_path = $this->locate( $template_name );

        if ( empty( $template_path ) ) {
            // Fail silently if the template is not found to prevent breaking the UI.
            // In a debug environment, this could be logged.
            return;
        }

        // Extract arguments safely. EXTR_SKIP ensures we don't overwrite existing variables.
        if ( ! empty( $args ) && is_array( $args ) ) {
            // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
            extract( $args, EXTR_SKIP );
        }

        include $template_path;
    }

    /**
     * Locate a template file and return its absolute path.
     * Includes strict security checks to prevent directory traversal attacks.
     *
     * @param string $template_name The template file name.
     *
     * @return string The absolute path to the template, or empty string if not found/invalid.
     */
    public function locate( string $template_name ): string {
        // Ensure the template name has a .php extension.
        if ( substr( $template_name, -4 ) !== '.php' ) {
            $template_name .= '.php';
        }

        // Construct the expected path.
        $path = $this->template_dir . ltrim( $template_name, '/\\' );

        // Resolve real paths to prevent directory traversal (e.g., ../../wp-config.php).
        $real_path           = realpath( $path );
        $real_template_dir   = realpath( $this->template_dir );

        // Verify the resolved path exists and is strictly within the templates directory.
        if ( 
            $real_path && 
            $real_template_dir && 
            strpos( $real_path, $real_template_dir ) === 0 && 
            file_exists( $real_path ) 
        ) {
            return $real_path;
        }

        return '';
    }

    /**
     * Escape a string for safe HTML output.
     *
     * @param string $text The text to escape.
     * @return string
     */
    public function esc_html( string $text ): string {
        return esc_html( $text );
    }

    /**
     * Escape a string for safe HTML attribute output.
     *
     * @param string $text The text to escape.
     * @return string
     */
    public function esc_attr( string $text ): string {
        return esc_attr( $text );
    }

    /**
     * Escape a URL for safe output.
     *
     * @param string $url The URL to escape.
     * @return string
     */
    public function esc_url( string $url ): string {
        return esc_url( $url );
    }

    /**
     * Filter content allowing only safe HTML tags (for rich text output).
     *
     * @param string $content The content to filter.
     * @return string
     */
    public function kses_post( string $content ): string {
        return wp_kses_post( $content );
    }
}