<?php
/**
 * Engine Manager
 *
 * Registers and coordinates all Modules. It acts as the single source of truth
 * for the list of active modules (read from the Manifest) and provides a 
 * lightweight mechanism for modules to register their services into the Container.
 *
 * @package SICSAP\Engine
 */

declare(strict_types=1);

namespace SICSAP\Engine;

defined( 'ABSPATH' ) || exit;

use SICSAP\Core\Container;
use SICSAP\Core\Manifest;

/**
 * Class EngineManager
 *
 * Central coordinator for all plugin modules.
 */
class EngineManager {

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * The manifest runtime accessor.
     *
     * @var Manifest
     */
    private Manifest $manifest;

    /**
     * List of active module names.
     *
     * @var string[]
     */
    private array $active_modules = [];

    /**
     * Registered initialization callbacks for each module.
     *
     * @var array<string, callable>
     */
    private array $module_callbacks = [];

    /**
     * Constructor.
     *
     * @param Container $container The DI container.
     * @param Manifest  $manifest  The manifest accessor.
     */
    public function __construct( Container $container, Manifest $manifest ) {
        $this->container = $container;
        $this->manifest  = $manifest;
    }

    /**
     * Initialize the engine manager.
     * Loads the active modules from the manifest and hooks into WordPress
     * to boot the modules at the appropriate time.
     *
     * @return void
     */
    public function initialize(): void {
        $this->active_modules = $this->manifest->get_modules();

        // Hook into the custom init action fired by the Application class.
        // This ensures modules are booted lazily after the core framework is ready.
        add_action( 'si_csap_init', [ $this, 'boot_modules' ], 20 );
    }

    /**
     * Register a module's initialization callback.
     * Modules will use this to bind their Services, Repositories, and Controllers
     * into the Container.
     *
     * @param string   $module_name The name of the module (must match manifest).
     * @param callable $callback    The callback to execute during boot. Receives the Container.
     *
     * @return void
     */
    public function register_module_callback( string $module_name, callable $callback ): void {
        if ( $this->is_module_active( $module_name ) ) {
            $this->module_callbacks[ $module_name ] = $callback;
        }
    }

    /**
     * Boot all registered modules.
     * Executes the registered callbacks, effectively binding module services
     * to the container.
     *
     * @return void
     */
    public function boot_modules(): void {
        foreach ( $this->module_callbacks as $module_name => $callback ) {
            if ( is_callable( $callback ) ) {
                // Pass the container to the module so it can bind its own services.
                call_user_func( $callback, $this->container );
            }
        }

        /**
         * Fires after all active modules have been booted and their services
         * registered in the container.
         */
        do_action( 'si_csap_modules_booted' );
    }

    /**
     * Get the list of all active modules.
     *
     * @return string[]
     */
    public function get_active_modules(): array {
        return $this->active_modules;
    }

    /**
     * Check if a specific module is active.
     *
     * @param string $module_name The module name to check.
     *
     * @return bool
     */
    public function is_module_active( string $module_name ): bool {
        return in_array( $module_name, $this->active_modules, true );
    }

    /**
     * Get the dependency injection container.
     *
     * @return Container
     */
    public function get_container(): Container {
        return $this->container;
    }
}