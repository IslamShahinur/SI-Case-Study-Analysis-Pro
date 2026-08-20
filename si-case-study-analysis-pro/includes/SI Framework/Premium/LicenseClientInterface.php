<?php
/**
 * License Client Interface
 *
 * Interface the plugin codes against, so the concrete SDK implementation
 * can be swapped/regenerated without touching plugin code.
 * LicenseController depends on this interface, not the concrete SDK class.
 *
 * @package SICSAP\Premium
 */

declare(strict_types=1);

namespace SICSAP\Premium;

defined( 'ABSPATH' ) || exit;

/**
 * Interface LicenseClientInterface
 *
 * Defines the strict contract for the licensing SDK client.
 * All methods must return a standardized array structure to allow
 * uniform handling in the LicenseController.
 */
interface LicenseClientInterface {

    /**
     * Activate a license key on the remote server.
     *
     * @param string $license_key The license key to activate.
     *
     * @return array{
     *     activated: bool,
     *     error: string|null,
     *     licensed_email?: string,
     *     entitlements?: array<string, bool>
     * }
     */
    public function activate( string $license_key ): array;

    /**
     * Deactivate a license key on the remote server.
     *
     * @param string $license_key The license key to deactivate.
     *
     * @return array{
     *     deactivated: bool,
     *     error: string|null
     * }
     */
    public function deactivate( string $license_key ): array;

    /**
     * Verify the status of a license key.
     *
     * @param string $license_key The license key to verify.
     * @param bool   $use_cache   Whether to use cached verification results (e.g., ~1 hour cache).
     *
     * @return array{
     *     success: bool,
     *     valid: bool,
     *     error: string|null,
     *     expires?: string,
     *     entitlements?: array<string, bool>
     * }
     */
    public function verify( string $license_key, bool $use_cache = true ): array;

    /**
     * Check for plugin updates from the remote server.
     *
     * @param string $current_version The current installed plugin version.
     *
     * @return array{
     *     success: bool,
     *     update_available: bool,
     *     error: string|null,
     *     download_url?: string,
     *     new_version?: string
     * }
     */
    public function check_update( string $current_version ): array;
}