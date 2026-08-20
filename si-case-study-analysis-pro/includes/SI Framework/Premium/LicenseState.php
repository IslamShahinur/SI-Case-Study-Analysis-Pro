<?php
/**
 * License State
 *
 * Persists and exposes the current license status, licensed email,
 * expiration date, and entitlement flags.
 * LicenseController writes to this state; FeatureManager reads from it.
 *
 * @package SICSAP\Premium
 */

declare(strict_types=1);

namespace SICSAP\Premium;

defined( 'ABSPATH' ) || exit;

/**
 * Class LicenseState
 *
 * Manages the persistent state of the plugin's license in the wp_options table.
 */
class LicenseState {

    /**
     * The option name used to store the license state in the database.
     *
     * @var string
     */
    private const OPTION_NAME = 'si_csap_license_state';

    /**
     * The current license status.
     * Possible values: 'active', 'inactive', 'expired', 'error', 'unknown'.
     *
     * @var string
     */
    private string $status;

    /**
     * The active license key.
     *
     * @var string
     */
    private string $license_key;

    /**
     * The email address associated with the license.
     *
     * @var string
     */
    private string $licensed_email;

    /**
     * The expiration date of the license (e.g., '2024-12-31' or 'lifetime').
     *
     * @var string
     */
    private string $expiration;

    /**
     * Array of entitlement flags (feature_name => bool).
     *
     * @var array<string, bool>
     */
    private array $entitlements;

    /**
     * Constructor.
     * Loads the state from the database or initializes with defaults.
     */
    public function __construct() {
        $this->load();
    }

    /**
     * Load the license state from the database.
     *
     * @return void
     */
    private function load(): void {
        $saved_state = get_option( self::OPTION_NAME, [] );

        if ( ! is_array( $saved_state ) ) {
            $saved_state = [];
        }

        $this->status         = isset( $saved_state['status'] ) && is_string( $saved_state['status'] ) ? sanitize_key( $saved_state['status'] ) : 'inactive';
        $this->license_key    = isset( $saved_state['license_key'] ) && is_string( $saved_state['license_key'] ) ? sanitize_text_field( $saved_state['license_key'] ) : '';
        $this->licensed_email = isset( $saved_state['licensed_email'] ) && is_string( $saved_state['licensed_email'] ) ? sanitize_email( $saved_state['licensed_email'] ) : '';
        $this->expiration     = isset( $saved_state['expiration'] ) && is_string( $saved_state['expiration'] ) ? sanitize_text_field( $saved_state['expiration'] ) : '';
        
        $this->entitlements   = [];
        if ( isset( $saved_state['entitlements'] ) && is_array( $saved_state['entitlements'] ) ) {
            foreach ( $saved_state['entitlements'] as $key => $value ) {
                $this->entitlements[ sanitize_key( (string) $key ) ] = (bool) $value;
            }
        }
    }

    /**
     * Save the current license state to the database.
     *
     * @return bool True if the option was updated, false otherwise.
     */
    public function save(): bool {
        $state_to_save = [
            'status'         => $this->status,
            'license_key'    => $this->license_key,
            'licensed_email' => $this->licensed_email,
            'expiration'     => $this->expiration,
            'entitlements'   => $this->entitlements,
        ];

        // Autoload is set to true (default) because license state is checked frequently.
        return update_option( self::OPTION_NAME, $state_to_save );
    }

    /**
     * Get the current license status.
     *
     * @return string
     */
    public function get_status(): string {
        return $this->status;
    }

    /**
     * Set the license status.
     *
     * @param string $status The status to set.
     * @return void
     */
    public function set_status( string $status ): void {
        $this->status = sanitize_key( $status );
    }

    /**
     * Get the active license key.
     *
     * @return string
     */
    public function get_license_key(): string {
        return $this->license_key;
    }

    /**
     * Set the license key.
     *
     * @param string $license_key The license key to set.
     * @return void
     */
    public function set_license_key( string $license_key ): void {
        $this->license_key = sanitize_text_field( $license_key );
    }

    /**
     * Get the licensed email address.
     *
     * @return string
     */
    public function get_licensed_email(): string {
        return $this->licensed_email;
    }

    /**
     * Set the licensed email address.
     *
     * @param string $email The email to set.
     * @return void
     */
    public function set_licensed_email( string $email ): void {
        $this->licensed_email = sanitize_email( $email );
    }

    /**
     * Get the license expiration date.
     *
     * @return string
     */
    public function get_expiration(): string {
        return $this->expiration;
    }

    /**
     * Set the license expiration date.
     *
     * @param string $expiration The expiration date to set.
     * @return void
     */
    public function set_expiration( string $expiration ): void {
        $this->expiration = sanitize_text_field( $expiration );
    }

    /**
     * Get all entitlement flags.
     *
     * @return array<string, bool>
     */
    public function get_entitlements(): array {
        return $this->entitlements;
    }

    /**
     * Set the entire entitlements array.
     *
     * @param array<string, bool> $entitlements The entitlements to set.
     * @return void
     */
    public function set_entitlements( array $entitlements ): void {
        $this->entitlements = [];
        foreach ( $entitlements as $key => $value ) {
            $this->entitlements[ sanitize_key( (string) $key ) ] = (bool) $value;
        }
    }

    /**
     * Get a specific entitlement flag.
     *
     * @param string $key     The entitlement key.
     * @param bool   $default The default value if the key does not exist.
     * 
     * @return bool
     */
    public function get_entitlement( string $key, bool $default = false ): bool {
        $key = sanitize_key( $key );
        return $this->entitlements[ $key ] ?? $default;
    }

    /**
     * Set a specific entitlement flag.
     *
     * @param string $key   The entitlement key.
     * @param bool   $value The value to set.
     * 
     * @return void
     */
    public function set_entitlement( string $key, bool $value ): void {
        $this->entitlements[ sanitize_key( $key ) ] = $value;
    }

    /**
     * Check if the license is currently active.
     *
     * @return bool
     */
    public function is_active(): bool {
        return $this->status === 'active';
    }

    /**
     * Reset the state to default inactive values.
     *
     * @return void
     */
    public function reset(): void {
        $this->status         = 'inactive';
        $this->license_key    = '';
        $this->licensed_email = '';
        $this->expiration     = '';
        $this->entitlements   = [];
    }
}