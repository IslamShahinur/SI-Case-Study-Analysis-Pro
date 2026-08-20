<?php
/**
 * Sample Integration Reference
 *
 * This file demonstrates how to interact with the SI License SDK.
 * IT IS NOT USED DIRECTLY IN PRODUCTION.
 * The relevant logic has been ported into LicenseController.php.
 *
 * @package SICSAP\Premium\SDK
 */

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Example: Activating a license.
 *
 * @param string $license_key The key to activate.
 *
 * @return array
 */
function si_sdk_sample_activate( string $license_key ): array {
    $client = new SI_License_Client();
    return $client->activate( $license_key );
}

/**
 * Example: Deactivating a license.
 *
 * @param string $license_key The key to deactivate.
 *
 * @return array
 */
function si_sdk_sample_deactivate( string $license_key ): array {
    $client = new SI_License_Client();
    return $client->deactivate( $license_key );
}

/**
 * Example: Verifying a license (with cache).
 *
 * @param string $license_key The key to verify.
 * @param bool   $use_cache   Whether to use the transient cache.
 *
 * @return array
 */
function si_sdk_sample_verify( string $license_key, bool $use_cache = true ): array {
    $client = new SI_License_Client();
    // Use cache (default). Pass false to force a fresh check.
    return $client->verify( $license_key, $use_cache );
}

/**
 * Example: Checking for updates.
 *
 * @param string $current_version Current plugin version.
 *
 * @return array
 */
function si_sdk_sample_check_update( string $current_version ): array {
    $client = new SI_License_Client();
    return $client->check_update( $current_version );
}

/**
 * Example: Hooking into admin_init for background verification.
 * 
 * Note: In production, periodic verification is handled via LicenseController 
 * and WP Cron/Transients, not directly in an admin_init hook like this.
 * This is purely for reference.
 *
 * @return void
 */
function si_sdk_sample_admin_init_check(): void {
    /*
    $state = new \SICSAP\Premium\LicenseState();
    $key   = $state->get_license_key();

    if ( ! empty( $key ) ) {
        $client = new SI_License_Client();
        $result = $client->verify( $key, true );

        if ( ! $result['success'] || ! $result['valid'] ) {
            // Handle invalid/expired license state here.
            // e.g., $state->set_status( 'expired' ); $state->save();
        }
    }
    */
}
// add_action( 'admin_init', 'si_sdk_sample_admin_init_check' );