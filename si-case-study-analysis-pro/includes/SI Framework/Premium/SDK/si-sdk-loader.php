<?php
/**
 * SDK Loader
 *
 * Single require_once entry point for the auto-generated License SDK.
 * This file loads the SDK configuration and the core SDK classes.
 * It is the ONLY file that should be required by the plugin to load the SDK.
 *
 * @package SICSAP\Premium\SDK
 */

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Load the SDK configuration.
 * Contains API base URL, namespace, product ID, and API key/secret.
 */
$sdk_config_path = __DIR__ . '/si-sdk-config.php';
if ( file_exists( $sdk_config_path ) ) {
    require_once $sdk_config_path;
}

/**
 * 2. Load the core SDK classes.
 * These are the auto-generated HTTP and License client classes.
 */
$api_client_path = __DIR__ . '/includes/class-si-api-client.php';
if ( file_exists( $api_client_path ) ) {
    require_once $api_client_path;
}

$license_client_path = __DIR__ . '/includes/class-si-license-client.php';
if ( file_exists( $license_client_path ) ) {
    require_once $license_client_path;
}