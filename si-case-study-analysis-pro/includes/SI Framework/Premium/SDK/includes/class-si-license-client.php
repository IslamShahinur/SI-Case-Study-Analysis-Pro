<?php
/**
 * SI License Client
 *
 * High-level license client implementing the LicenseClientInterface.
 * Provides verify(), activate(), deactivate(), and check_update() methods.
 * Caches verification results to reduce API calls.
 *
 * @package SICSAP\Premium\SDK
 */

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class SI_License_Client
 *
 * Implements the LicenseClientInterface using the SI_API_Client for HTTP transport.
 */
class SI_License_Client implements \SICSAP\Premium\LicenseClientInterface {

    /**
     * The low-level HTTP API client.
     *
     * @var SI_API_Client
     */
    private SI_API_Client $api_client;

    /**
     * Cache TTL for verification results in seconds.
     *
     * @var int
     */
    private int $cache_ttl;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->api_client = new SI_API_Client();
        $this->cache_ttl  = defined( 'SI_SDK_VERIFY_CACHE_TTL' ) ? (int) SI_SDK_VERIFY_CACHE_TTL : HOUR_IN_SECONDS;
    }

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
    public function activate( string $license_key ): array {
        $response = $this->api_client->post( '/activate', [
            'license_key' => $license_key,
            'product_id'  => defined( 'SI_SDK_PRODUCT_ID' ) ? SI_SDK_PRODUCT_ID : '',
            'site_url'    => home_url(),
        ] );

        if ( ! $response['success'] ) {
            return [
                'activated' => false,
                'error'     => $response['error'] ?? __( 'Activation request failed.', 'si-csap' ),
            ];
        }

        $data = $response['data'] ?? [];

        // Clear verification cache upon successful activation.
        $this->clear_verification_cache( $license_key );

        return [
            'activated'      => true,
            'error'          => null,
            'licensed_email' => isset( $data['email'] ) ? sanitize_email( (string) $data['email'] ) : '',
            'entitlements'   => isset( $data['entitlements'] ) && is_array( $data['entitlements'] ) ? $data['entitlements'] : [],
        ];
    }

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
    public function deactivate( string $license_key ): array {
        $response = $this->api_client->post( '/deactivate', [
            'license_key' => $license_key,
            'product_id'  => defined( 'SI_SDK_PRODUCT_ID' ) ? SI_SDK_PRODUCT_ID : '',
            'site_url'    => home_url(),
        ] );

        if ( ! $response['success'] ) {
            return [
                'deactivated' => false,
                'error'       => $response['error'] ?? __( 'Deactivation request failed.', 'si-csap' ),
            ];
        }

        // Clear verification cache upon successful deactivation.
        $this->clear_verification_cache( $license_key );

        return [
            'deactivated' => true,
            'error'       => null,
        ];
    }

    /**
     * Verify the status of a license key.
     *
     * @param string $license_key The license key to verify.
     * @param bool   $use_cache   Whether to use cached verification results.
     *
     * @return array{
     *     success: bool,
     *     valid: bool,
     *     error: string|null,
     *     expires?: string,
     *     entitlements?: array<string, bool>
     * }
     */
    public function verify( string $license_key, bool $use_cache = true ): array {
        $cache_key = $this->get_cache_key( $license_key );

        // Attempt to retrieve from cache.
        if ( $use_cache ) {
            $cached = get_transient( $cache_key );
            if ( false !== $cached && is_array( $cached ) ) {
                return $cached;
            }
        }

        $response = $this->api_client->post( '/verify', [
            'license_key' => $license_key,
            'product_id'  => defined( 'SI_SDK_PRODUCT_ID' ) ? SI_SDK_PRODUCT_ID : '',
            'site_url'    => home_url(),
        ] );

        if ( ! $response['success'] ) {
            $result = [
                'success' => false,
                'valid'   => false,
                'error'   => $response['error'] ?? __( 'Verification request failed.', 'si-csap' ),
            ];
            
            // Cache the failure state briefly to avoid hammering the server on network issues.
            set_transient( $cache_key, $result, 5 * MINUTE_IN_SECONDS );
            
            return $result;
        }

        $data       = $response['data'] ?? [];
        $is_valid   = ! empty( $data['valid'] );

        $result = [
            'success'      => true,
            'valid'        => $is_valid,
            'error'        => null,
            'expires'      => isset( $data['expires'] ) ? sanitize_text_field( (string) $data['expires'] ) : '',
            'entitlements' => isset( $data['entitlements'] ) && is_array( $data['entitlements'] ) ? $data['entitlements'] : [],
        ];

        // Cache successful verification results.
        if ( $is_valid ) {
            set_transient( $cache_key, $result, $this->cache_ttl );
        }

        return $result;
    }

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
    public function check_update( string $current_version ): array {
        $response = $this->api_client->get( '/product', [
            'product_id'      => defined( 'SI_SDK_PRODUCT_ID' ) ? SI_SDK_PRODUCT_ID : '',
            'current_version' => $current_version,
        ] );

        if ( ! $response['success'] ) {
            return [
                'success'          => false,
                'update_available' => false,
                'error'            => $response['error'] ?? __( 'Update check request failed.', 'si-csap' ),
            ];
        }

        $data             = $response['data'] ?? [];
        $new_version      = isset( $data['version'] ) ? sanitize_text_field( (string) $data['version'] ) : '';
        $update_available = ! empty( $new_version ) && version_compare( $new_version, $current_version, '>' );

        return [
            'success'          => true,
            'update_available' => $update_available,
            'error'            => null,
            'download_url'     => isset( $data['download_url'] ) ? esc_url_raw( (string) $data['download_url'] ) : '',
            'new_version'      => $new_version,
        ];
    }

    /**
     * Generate a unique cache key for a license key.
     *
     * @param string $license_key The license key.
     *
     * @return string
     */
    private function get_cache_key( string $license_key ): string {
        $product_id = defined( 'SI_SDK_PRODUCT_ID' ) ? SI_SDK_PRODUCT_ID : 'default';
        // Hash the license key to keep transient names short and secure.
        return 'si_csap_verify_' . md5( $license_key . $product_id );
    }

    /**
     * Clear the verification cache for a specific license key.
     *
     * @param string $license_key The license key.
     *
     * @return void
     */
    private function clear_verification_cache( string $license_key ): void {
        delete_transient( $this->get_cache_key( $license_key ) );
    }
}