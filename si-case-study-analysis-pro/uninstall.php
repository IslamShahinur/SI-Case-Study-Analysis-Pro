<?php
/**
 * Uninstall script for SI Case Study Analysis Pro.
 *
 * Fires when the plugin is deleted via the WordPress admin.
 * This is an opt-in destructive uninstall that removes ONLY plugin-owned data.
 * It must never touch unrelated WordPress data or posts.
 *
 * @package SICSAP
 */

declare(strict_types=1);

// Exit if not called by WordPress uninstall process.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

/**
 * 1. Remove Plugin Options and Transients
 * We use a direct $wpdb query to find and delete all options and transients 
 * starting with our specific prefix to ensure no unrelated data is touched.
 */
$wpdb->query( 
    $wpdb->prepare( 
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", 
        'si_csap_%', 
        '_si_csap_%' 
    ) 
);

// Remove transients (both transient and transient timeout records)
$wpdb->query( 
    $wpdb->prepare( 
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", 
        '_transient_si_csap_%', 
        '_transient_timeout_si_csap_%' 
    ) 
);

/**
 * 2. Remove User Meta (if any plugin-specific user meta was saved)
 */
$wpdb->query( 
    $wpdb->prepare( 
        "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE %s", 
        'si_csap_%' 
    ) 
);

/**
 * 3. Drop Custom Database Tables (Opt-in)
 * Custom database tables are only dropped if the user has explicitly opted in 
 * via the plugin settings prior to uninstallation.
 */
$delete_db_data = get_option( 'si_csap_delete_data_on_uninstall', false );

if ( $delete_db_data ) {
    // Note: Table names will be formally defined and added here as they are created in subsequent phases.
    // Example structure for when tables are introduced:
    /*
    $tables_to_drop = [
        $wpdb->prefix . 'si_csap_cases',
        $wpdb->prefix . 'si_csap_evidence',
        // Add other tables here as they are created.
    ];
    
    foreach ( $tables_to_drop as $table ) {
        $wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    }
    */
}

/**
 * 4. Clear Cache
 * Flush the object cache to ensure no stale plugin data remains in memory.
 */
wp_cache_flush();