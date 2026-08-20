<?php
/**
 * Minimal Dependency Injection Container.
 *
 * Provides service binding and resolution with support for singletons
 * and lazy loading via closures. Keeps the bootstrap lightweight by
 * only instantiating services when they are actually requested.
 *
 * @package SICSAP\Core
 */

declare(strict_types=1);

namespace SICSAP\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Class Container
 *
 * Core DI container for the SI Framework v3.0.
 */
class Container {

    /**
     * Registered bindings.
     *
     * @var array<string, array{concrete: callable|string, shared: bool}>
     */
    private array $bindings = [];

    /**
     * Resolved singleton instances.
     *
     * @var array<string, mixed>
     */
    private array $instances = [];

    /**
     * Bind an identifier to a concrete implementation.
     *
     * @param string          $id       The identifier (usually class name or interface).
     * @param callable|string $concrete The concrete implementation (closure or class name).
     * @param bool            $shared   Whether to resolve as a singleton.
     *
     * @return void
     */
    public function bind( string $id, $concrete, bool $shared = false ): void {
        $this->bindings[ $id ] = [
            'concrete' => $concrete,
            'shared'   => $shared,
        ];
    }

    /**
     * Bind an identifier as a singleton.
     *
     * @param string          $id       The identifier.
     * @param callable|string $concrete The concrete implementation.
     *
     * @return void
     */
    public function singleton( string $id, $concrete ): void {
        $this->bind( $id, $concrete, true );
    }

    /**
     * Resolve an identifier from the container.
     *
     * @param string $id The identifier to resolve.
     *
     * @return mixed The resolved instance.
     * 
     * @throws \Exception If the identifier is not bound.
     */
    public function get( string $id ) {
        // Return existing singleton instance if available.
        if ( isset( $this->instances[ $id ] ) ) {
            return $this->instances[ $id ];
        }

        // Check if the identifier is bound.
        if ( ! $this->has( $id ) ) {
            throw new \Exception( 
                sprintf( 
                    /* translators: %s: The unbound identifier. */
                    esc_html__( 'Identifier "%s" is not bound to the container.', 'si-csap' ), 
                    esc_html( $id ) 
                ) 
            );
        }

        $binding  = $this->bindings[ $id ];
        $concrete = $binding['concrete'];
        $shared   = $binding['shared'];

        // Resolve the concrete implementation.
        if ( is_callable( $concrete ) ) {
            // Pass the container to the closure to allow nested dependency resolution.
            $instance = $concrete( $this );
        } elseif ( is_string( $concrete ) && class_exists( $concrete ) ) {
            $instance = new $concrete();
        } else {
            $instance = $concrete;
        }

        // Store as singleton if shared.
        if ( $shared ) {
            $this->instances[ $id ] = $instance;
        }

        return $instance;
    }

    /**
     * Check if an identifier is bound in the container.
     *
     * @param string $id The identifier to check.
     *
     * @return bool True if bound, false otherwise.
     */
    public function has( string $id ): bool {
        return isset( $this->bindings[ $id ] );
    }
}