<?php
/**
 * License Controller
 *
 * Plugin-side orchestrator for the licensing system.
 * This is the ONLY class in the plugin allowed to call the Licensing SDK directly.
 * It handles activation, deactivation, and verification, and writes the results
 * into the LicenseState.
 *
 * @package SICSAP\Premium
 */

declare(strict_types=1);

namespace SICSAP\Premium;

defined( 'ABSPATH' ) || exit;

/**
 * Class LicenseController
 *
 * Orchestrates all interactions between the plugin and the Licensing SDK.
 */
class LicenseController {

    /**
     * The SDK client interface.
     *
     * @var LicenseClientInterface
     */
    private LicenseClientInterface $client;

    /**
     * The license state manager.
     *
     * @var LicenseState
     */
    private LicenseState $state;

    /**
     * Constructor.
     *
     * @param LicenseClientInterface $client The SDK client.
     * @param LicenseState           $state  The license state manager.
     */
    public function __construct( LicenseClientInterface $client, LicenseState $state ) {
        $this->client = $client;
        $this->state  = $state;
    }

    /**
     * Activate a license key.
     *
     * @param string $license_key The license key to activate.
     *
     * @return array{success: bool, message: string}
     */
    public function activate_license( string $license_key ): array {
        if ( empty( $license_key ) ) {
            return [
                'success' => false,
                'message' => __( 'License key cannot be empty.', 'si-csap' ),
            ];
        }

        // Call the SDK to activate the license.
        $response = $this->client->activate( $license_key );

        // Uniformly handle the SDK response.
        if ( empty( $response['activated'] ) || ! empty( $response['error'] ) ) {
            $error_message = ! empty( $response['error'] ) 
                ? $response['error'] 
                : __( 'Activation failed. Please check your license key and try again.', 'si-csap' );

            return [
                'success' => false,
                'message' => $error_message,
            ];
        }

        // Activation successful. Update the state.
        $this->state->set_status( 'active' );
        $this->state->set_license_key( $license_key );
        
        // Extract and save licensed email if provided by the SDK.
        if ( ! empty( $response['licensed_email'] ) ) {
            $this->state->set_licensed_email( sanitize_email( $response['licensed_email'] ) );
        }

        // Extract and save entitlements if provided.
        if ( ! empty( $response['entitlements'] ) && is_array( $response['entitlements'] ) ) {
            $this->state->set_entitlements( $response['entitlements'] );
        }

        $this->state->save();

        return [
            'success' => true,
            'message' => __( 'License activated successfully.', 'si-csap' ),
        ];
    }

    /**
     * Deactivate the current license.
     *
     * @return array{success: bool, message: string}
     */
    public function deactivate_license(): array {
        $current_key = $this->state->get_license_key();

        if ( empty( $current_key ) ) {
            // If there's no key, just reset the state locally.
            $this->reset_local_state();
            return [
                'success' => true,
                'message' => __( 'License deactivated.', 'si-csap' ),
            ];
        }

        // Call the SDK to deactivate.
        $response = $this->client->deactivate( $current_key );

        // Uniformly handle the SDK response.
        if ( empty( $response['deactivated'] ) && ! empty( $response['error'] ) ) {
            return [
                'success' => false,
                'message' => $response['error'],
            ];
        }

        // Deactivation successful (or forced local reset if SDK failed but we want to clear it).
        $this->reset_local_state();

        return [
            'success' => true,
            'message' => __( 'License deactivated successfully.', 'si-csap' ),
        ];
    }

    /**
     * Verify the current license status with the server.
     *
     * @param bool $force_check Whether to bypass the cache and force a fresh check.
     *
     * @return array{success: bool, message: string, status: string}
     */
    public function verify_license( bool $force_check = false ): array {
        $current_key = $this->state->get_license_key();

        if ( empty( $current_key ) ) {
            return [
                'success' => false,
                'message' => __( 'No license key is currently active.', 'si-csap' ),
                'status'  => 'inactive',
            ];
        }

        // Call the SDK to verify.
        $response = $this->client->verify( $current_key, ! $force_check );

        // Uniformly handle the SDK response.
        if ( empty( $response['success'] ) && empty( $response['valid'] ) ) {
            $error_message = ! empty( $response['error'] ) 
                ? $response['error'] 
                : __( 'License verification failed.', 'si-csap' );

            // If verification fails due to network/server error, we don't immediately 
            // deactivate, but we mark the status as 'unknown' or 'error'.
            $this->state->set_status( 'error' );
            $this->state->save();

            return [
                'success' => false,
                'message' => $error_message,
                'status'  => 'error',
            ];
        }

        // License is valid. Update state.
        $this->state->set_status( 'active' );

        // Update entitlements if they were returned in the verification response.
        if ( ! empty( $response['entitlements'] ) && is_array( $response['entitlements'] ) ) {
            $this->state->set_entitlements( $response['entitlements'] );
        }

        // Update expiration if provided.
        if ( ! empty( $response['expires'] ) ) {
            $this->state->set_expiration( sanitize_text_field( $response['expires'] ) );
        }

        $this->state->save();

        return [
            'success' => true,
            'message' => __( 'License is valid and active.', 'si-csap' ),
            'status'  => 'active',
        ];
    }

    /**
     * Get the current license status and details.
     *
     * @return array{status: string, key: string, email: string, expires: string, entitlements: array}
     */
    public function get_license_details(): array {
        return [
            'status'       => $this->state->get_status(),
            'key'          => $this->state->get_license_key(),
            'email'        => $this->state->get_licensed_email(),
            'expires'      => $this->state->get_expiration(),
            'entitlements' => $this->state->get_entitlements(),
        ];
    }

    /**
     * Reset the local license state to inactive.
     *
     * @return void
     */
    private function reset_local_state(): void {
        $this->state->set_status( 'inactive' );
        $this->state->set_license_key( '' );
        $this->state->set_licensed_email( '' );
        $this->state->set_entitlements( [] );
        $this->state->set_expiration( '' );
        $this->state->save();
    }
}