<?php
/**
 * Boot Orchestrator
 *
 * Sequences the initialization of the SI Framework v3.0.
 * Order: Manifest -> Requirements -> Container -> Loader -> Application.
 * Keeps the initial boot lightweight by deferring heavy operations to WP hooks.
 *
 * @package SICSAP\Core
 */

declare(strict_types=1);

namespace SICSAP\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Class Bootstrap
 *
 * The entry point for the framework's initialization sequence.
 */
class Bootstrap {

    /**
     * Boot the application.
     *
     * This is the single method called from the main plugin file (si-case-study-analysis-pro.php).
     *
     * @return void
     */
    public static function boot(): void {
        // 1. Load and instantiate the Manifest.
        $manifest_data = require SI_CSAP_PLUGIN_DIR . 'manifest.php';
        $manifest      = new Manifest( $manifest_data );

        // 2. Verify environment requirements (PHP/WP versions, extensions).
        // If this fails, the Requirements class halts the boot and shows an admin notice.
        $requirements = new Requirements(
            $manifest->get( 'min_php' ),
            $manifest->get( 'min_wp' ),
            self::get_required_extensions()
        );

        if ( ! $requirements->check() ) {
            return;
        }

        // 3. Initialize the Dependency Injection Container.
        $container = new Container();

        // 4. Initialize and register the PSR-4 Autoloader.
        $loader = new Loader();
        $loader->register();

        // 5. Bind core framework services to the Container as singletons.
        $container->singleton( Manifest::class, function () use ( $manifest ) {
            return $manifest;
        } );

        $container->singleton( Loader::class, function () use ( $loader ) {
            return $loader;
        } );

        // 6. Initialize the central Application object.
        $application = new Application( $container );
        
        $container->singleton( Application::class, function () use ( $application ) {
            return $application;
        } );

        // 7. Boot the Application (registers core WordPress hooks).
        $application->boot();
    }

    /**
     * Get the list of required PHP extensions.
     *
     * @return string[]
     */
    private static function get_required_extensions(): array {
        return [
            'json',
            'mbstring',
            'openssl',
        ];
    }
}