<?php
/**
 * Central Application Object
 *
 * Owns the Dependency Injection Container and acts as the main orchestrator
 * for WordPress hooks. It keeps the initial boot lightweight by deferring
 * heavy operations (like module loading and license checks) to appropriate
 * WordPress action hooks.
 *
 * @package SICSAP\Core
 */

declare(strict_types=1);

namespace SICSAP\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Class Application
 *
 * The core application instance that binds the Container to WordPress lifecycle hooks.
 */
class Application {

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Constructor.
     *
     * @param Container $container The DI container instance.
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Boot the application.
     *
     * Registers all core WordPress hooks. This method is called by Bootstrap
     * immediately after the application is instantiated.
     *
     * @return void
     */
    public function boot(): void {
        $this->register_hooks();
    }

    /**
     * Get the dependency injection container.
     *
     * @return Container
     */
    public function get_container(): Container {
        return $this->container;
    }

    /**
     * Register core WordPress hooks.
     *
     * @return void
     */
    private function register_hooks(): void {
        // General initialization (runs on every page load, front and back).
        add_action( 'init', [ $this, 'on_init' ] );

        // Admin-specific initialization.
        add_action( 'admin_init', [ $this, 'on_admin_init' ] );

        // REST API initialization.
        add_action( 'rest_api_init', [ $this, 'on_rest_api_init' ] );
    }

    /**
     * Callback for the 'init' hook.
     *
     * Handles general initialization tasks like loading the text domain.
     * Future: This is where the EngineManager will be triggered to register modules.
     *
     * @return void
     */
    public function on_init(): void {
        // Load plugin text domain for translations.
        load_plugin_textdomain( 
            SI_CSAP_TEXT_DOMAIN, 
            false, 
            dirname( plugin_basename( SI_CSAP_PLUGIN_FILE ) ) . '/languages' 
        );

        /**
         * Fires after the core application has initialized on the 'init' hook.
         * Modules and extensions can hook into this to perform their own initialization.
         */
        do_action( 'si_csap_init' );
    }

    /**
     * Callback for the 'admin_init' hook.
     *
     * Handles admin-specific initialization tasks.
     * Future: Settings registration, admin notices, and license verification checks.
     *
     * @return void
     */
    public function on_admin_init(): void {
        /**
         * Fires after the core application has initialized on the 'admin_init' hook.
         */
        do_action( 'si_csap_admin_init' );
    }

    /**
     * Callback for the 'rest_api_init' hook.
     *
     * Handles REST API specific initialization.
     * Future: Base REST namespace registration or global REST authentication hooks.
     *
     * @return void
     */
    public function on_rest_api_init(): void {
        /**
         * Fires after the core application has initialized on the 'rest_api_init' hook.
         */
        do_action( 'si_csap_rest_api_init' );
    }
}