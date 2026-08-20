<?php
/**
 * Manifest Runtime Accessor
 *
 * Provides runtime access to the plugin metadata defined in manifest.php.
 * Used by Requirements, EngineManager, and other core components to
 * retrieve version, module registry, and feature flags.
 *
 * @package SICSAP\Core
 */

declare(strict_types=1);

namespace SICSAP\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Class Manifest
 *
 * Runtime accessor for the plugin manifest data.
 */
class Manifest {

    /**
     * The manifest data array.
     *
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * Constructor.
     *
     * @param array<string, mixed> $data The manifest data loaded from manifest.php.
     */
    public function __construct( array $data ) {
        $this->data = $data;
    }

    /**
     * Get a specific value from the manifest by key.
     *
     * @param string $key     The key to retrieve.
     * @param mixed  $default The default value if the key does not exist.
     *
     * @return mixed
     */
    public function get( string $key, $default = null ) {
        return $this->data[ $key ] ?? $default;
    }

    /**
     * Get the plugin version.
     *
     * @return string
     */
    public function get_version(): string {
        return (string) $this->get( 'version', '1.0.0' );
    }

    /**
     * Get the plugin text domain.
     *
     * @return string
     */
    public function get_text_domain(): string {
        return (string) $this->get( 'text_domain', 'si-csap' );
    }

    /**
     * Get the minimum required PHP version.
     *
     * @return string
     */
    public function get_min_php(): string {
        return (string) $this->get( 'min_php', '7.4' );
    }

    /**
     * Get the minimum required WordPress version.
     *
     * @return string
     */
    public function get_min_wp(): string {
        return (string) $this->get( 'min_wp', '5.8' );
    }

    /**
     * Get the list of registered modules.
     *
     * @return string[]
     */
    public function get_modules(): array {
        $modules = $this->get( 'modules', [] );
        return is_array( $modules ) ? $modules : [];
    }

    /**
     * Check if a specific module is registered in the manifest.
     *
     * @param string $module_name The module name to check.
     *
     * @return bool
     */
    public function has_module( string $module_name ): bool {
        return in_array( $module_name, $this->get_modules(), true );
    }

    /**
     * Get the entire manifest data array.
     *
     * @return array<string, mixed>
     */
    public function get_all(): array {
        return $this->data;
    }
}