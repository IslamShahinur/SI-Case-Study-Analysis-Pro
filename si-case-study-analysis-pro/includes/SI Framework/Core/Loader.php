<?php
/**
 * PSR-4 Autoloader
 *
 * Maps the SICSAP\ namespace to the physical directory structure.
 * Specifically handles the 'SI Framework' directory (which contains a space)
 * to ensure valid PHP namespaces while maintaining the required folder structure.
 *
 * @package SICSAP\Core
 */

declare(strict_types=1);

namespace SICSAP\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Class Loader
 *
 * Handles autoloading of all plugin classes.
 */
class Loader {

    /**
     * Map of top-level namespace directories to their physical paths.
     *
     * @var array<string, string>
     */
    private array $base_dir_map;

    /**
     * Constructor.
     * Initializes the directory map based on the plugin root.
     */
    public function __construct() {
        $this->base_dir_map = [
            'Core'      => SI_CSAP_PLUGIN_DIR . 'includes/SI Framework/Core/',
            'Engine'    => SI_CSAP_PLUGIN_DIR . 'includes/SI Framework/Engine/',
            'Premium'   => SI_CSAP_PLUGIN_DIR . 'includes/SI Framework/Premium/',
            'Modules'   => SI_CSAP_PLUGIN_DIR . 'includes/Modules/',
            'Admin'     => SI_CSAP_PLUGIN_DIR . 'includes/Admin/',
            'Gutenberg' => SI_CSAP_PLUGIN_DIR . 'includes/Gutenberg/',
        ];
    }

    /**
     * Register the autoloader with the SPL autoloader stack.
     *
     * @return void
     */
    public function register(): void {
        spl_autoload_register( [ $this, 'load_class' ] );
    }

    /**
     * Unregister the autoloader (useful for testing).
     *
     * @return void
     */
    public function unregister(): void {
        spl_autoload_unregister( [ $this, 'load_class' ] );
    }

    /**
     * Load a specific class file.
     *
     * @param string $class The fully-qualified class name.
     * 
     * @return bool True if loaded, false otherwise.
     */
    public function load_class( string $class ): bool {
        $prefix = 'SICSAP\\';
        $len    = strlen( $prefix );

        // Check if the class uses the SICSAP namespace prefix.
        if ( strncmp( $prefix, $class, $len ) !== 0 ) {
            return false;
        }

        // Get the relative class name (everything after SICSAP\).
        $relative_class = substr( $class, $len );
        $parts          = explode( '\\', $relative_class );

        // We expect at least a top-level directory and a class name (e.g., Core\Bootstrap).
        if ( count( $parts ) < 2 ) {
            return false;
        }

        $top_level = $parts[0];

        // Check if we have a mapped directory for this top-level namespace.
        if ( ! isset( $this->base_dir_map[ $top_level ] ) ) {
            return false;
        }

        $base_dir  = $this->base_dir_map[ $top_level ];
        
        // Convert namespace separators to directory separators for the file path.
        $file_path = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

        // If the file exists, require it.
        if ( file_exists( $file_path ) ) {
            require $file_path;
            return true;
        }

        return false;
    }
}