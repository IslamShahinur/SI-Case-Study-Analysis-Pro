<?php
/**
 * Uninstall Handler
 *
 * @package SI_CSAP
 * @since 1.0.0
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

if (get_option('si_cap_preserve_data_on_uninstall')) {
    return;
}

global $wpdb;

$tables = [
    'si_cap_cases', 'si_cap_contexts', 'si_cap_sources', 'si_cap_data_points',
    'si_cap_evidence', 'si_cap_arguments', 'si_cap_timeline', 'si_cap_reports',
    'si_cap_team', 'si_cap_workflow', 'si_cap_activity_log', 'si_cap_ai_jobs'
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$table}");
}

$options = [
    'si_cap_version', 'si_cap_license_key', 'si_cap_license_status',
    'si_cap_ai_api_key', 'si_cap_preserve_data_on_uninstall'
];

foreach ($options as $option) {
    delete_option($option);
}

wp_clear_scheduled_hook('si_cap_daily_cleanup');
wp_clear_scheduled_hook('si_cap_process_ai_job');
