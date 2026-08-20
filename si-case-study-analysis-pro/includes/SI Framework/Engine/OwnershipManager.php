<?php
/**
 * Ownership Manager
 *
 * Mandatory mutation gateway. Enforces that the current user owns the 
 * specific resource (Case) they are attempting to mutate.
 * 
 * Flow: Request → Capability → Nonce → Validation → OwnershipManager → Repository → Mutation
 *
 * @package SICSAP\Engine
 */

declare(strict_types=1);

namespace SICSAP\Engine;

defined( 'ABSPATH' ) || exit;

/**
 * Class OwnershipManager
 *
 * The single gateway for all data mutations to ensure case isolation 
 * and strict ownership enforcement.
 */
class OwnershipManager {

    /**
     * Execute a mutation callback after verifying ownership of the given Case.
     *
     * @param int      $case_id           The ID of the case being mutated.
     * @param callable $mutation_callback The repository method to execute.
     * @param array    $args              Arguments to pass to the callback.
     *
     * @return mixed|\WP_Error The result of the callback, or WP_Error on ownership failure.
     */
    public function mutate_case( int $case_id, callable $mutation_callback, array $args = [] ) {
        // 1. Verify ownership directly against the database.
        if ( ! $this->current_user_owns_case( $case_id ) ) {
            return new \WP_Error(
                'si_csap_ownership_violation',
                __( 'You do not have permission to modify this case.', 'si-csap' ),
                [ 'status' => 403 ]
            );
        }

        // 2. Execute the mutation.
        return call_user_func_array( $mutation_callback, $args );
    }

    /**
     * Check if the current user owns the specified case.
     *
     * @param int $case_id The case ID to check.
     *
     * @return bool True if the user owns the case, false otherwise.
     */
    public function current_user_owns_case( int $case_id ): bool {
        $user_id = get_current_user_id();

        // Unauthenticated users cannot own anything.
        if ( $user_id === 0 ) {
            return false;
        }

        // Super admins on multisite can bypass ownership checks.
        if ( is_multisite() && is_super_admin() ) {
            return true;
        }

        // Fetch the owner_id directly from the database to prevent spoofing.
        // We do not rely on data passed from the Controller to ensure integrity.
        global $wpdb;
        $table_name = $wpdb->prefix . 'si_csap_cases';

        // Fail closed: If the table doesn't exist yet (e.g., during early activation), 
        // we cannot verify ownership, so we deny access.
        if ( ! $this->table_exists( $table_name ) ) {
            return false;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $owner_id = (int) $wpdb->get_var( 
            $wpdb->prepare( 
                "SELECT owner_id FROM {$table_name} WHERE id = %d LIMIT 1", 
                $case_id 
            ) 
        );

        return $owner_id === $user_id;
    }

    /**
     * Assert ownership, throwing an exception if the check fails.
     * Useful for contexts where a WP_Error is not appropriate (e.g., internal service calls).
     *
     * @param int $case_id The case ID to check.
     *
     * @throws \Exception If the user does not own the case.
     *
     * @return void
     */
    public function assert_ownership( int $case_id ): void {
        if ( ! $this->current_user_owns_case( $case_id ) ) {
            throw new \Exception( 
                esc_html__( 'Ownership violation: User does not own the specified case.', 'si-csap' ) 
            );
        }
    }

    /**
     * Check if a specific database table exists.
     *
     * @param string $table_name The table name to check.
     *
     * @return bool
     */
    private function table_exists( string $table_name ): bool {
        global $wpdb;
        
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $result = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );
        
        return $result === $table_name;
    }
}