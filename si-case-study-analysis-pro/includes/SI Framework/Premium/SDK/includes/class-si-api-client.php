<?php
/**
 * SI API Client
 *
 * Low-level HTTP client for the SI License Manager API.
 * Signs every request with HMAC-SHA256 headers.
 *
 * @package SICSAP\Premium\SDK
 */

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class SI_API_Client
 *
 * Handles HTTP requests and cryptographic signing for the License SDK.
 */
class SI_API_Client {

    /**
     * The base URL for the API.
     *
     * @var string
     */
    private string $base_url;

    /**
     * The product API key.
     *
     * @var string
     */
    private string $api_key;

    /**
     * The product API secret (used for HMAC signing).
     *
     * @var string
     */
    private string $api_secret;

    /**
     * Request timeout in seconds.
     *
     * @var int
     */
    private int $timeout;

    /**
     * Constructor.
     * Initializes configuration from the SDK config constants.
     */
    public function __construct() {
        $this->base_url   = defined( 'SI_SDK_API_BASE_URL' ) ? trailingslashit( SI_SDK_API_BASE_URL ) : '';
        $this->api_key    = defined( 'SI_SDK_API_KEY' ) ? SI_SDK_API_KEY : '';
        $this->api_secret = defined( 'SI_SDK_API_SECRET' ) ? SI_SDK_API_SECRET : '';
        $this->timeout    = defined( 'SI_SDK_TIMEOUT' ) ? (int) SI_SDK_TIMEOUT : 15;
    }

    /**
     * Send a GET request.
     *
     * @param string $route The API route (e.g., '/product').
     * @param array  $body  Optional query parameters.
     *
     * @return array{success: bool, data: array|null, error: string|null}
     */
    public function get( string $route, array $body = [] ): array {
        return $this->request( 'GET', $route, $body );
    }

    /**
     * Send a POST request.
     *
     * @param string $route The API route (e.g., '/verify').
     * @param array  $body  The request body.
     *
     * @return array{success: bool, data: array|null, error: string|null}
     */
    public function post( string $route, array $body = [] ): array {
        return $this->request( 'POST', $route, $body );
    }

    /**
     * Execute an HTTP request with HMAC-SHA256 signing.
     *
     * @param string $method The HTTP method (GET, POST, etc.).
     * @param string $route  The API route.
     * @param array  $body   The request body/parameters.
     *
     * @return array{success: bool, data: array|null, error: string|null}
     */
    private function request( string $method, string $route, array $body = [] ): array {
        if ( empty( $this->base_url ) || empty( $this->api_key ) || empty( $this->api_secret ) ) {
            return [
                'success' => false,
                'data'    => null,
                'error'   => 'SDK configuration is missing or incomplete.',
            ];
        }

        $url       = $this->base_url . ltrim( $route, '/' );
        $timestamp = (string) time();
        $method    = strtoupper( $method );

        // Sort the body array recursively to ensure deterministic JSON for signing.
        $sorted_body = $this->sort_array_recursive( $body );
        $json_body   = wp_json_encode( $sorted_body );

        // Create the signature string: METHOD\nROUTE\nTIMESTAMP\nSORTED_JSON_BODY
        $signature_string = implode( "\n", [
            $method,
            $route,
            $timestamp,
            $json_body,
        ] );

        // Generate HMAC-SHA256 signature.
        $signature = hash_hmac( 'sha256', $signature_string, $this->api_secret );

        // Prepare headers.
        $headers = [
            'Content-Type'   => 'application/json',
            'X-SI-API-Key'   => $this->api_key,
            'X-SI-Timestamp' => $timestamp,
            'X-SI-Signature' => $signature,
        ];

        // Prepare request arguments.
        $args = [
            'method'      => $method,
            'headers'     => $headers,
            'timeout'     => $this->timeout,
            'sslverify'   => true,
        ];

        if ( 'POST' === $method && ! empty( $body ) ) {
            $args['body'] = $json_body;
        } elseif ( 'GET' === $method && ! empty( $body ) ) {
            $url = add_query_arg( $body, $url );
        }

        // Execute the request.
        $response = wp_remote_request( $url, $args );

        // Handle WP_Error (network failures, timeouts, etc.).
        if ( is_wp_error( $response ) ) {
            return [
                'success' => false,
                'data'    => null,
                'error'   => $response->get_error_message(),
            ];
        }

        $status_code = wp_remote_retrieve_response_code( $response );
        $raw_body    = wp_remote_retrieve_body( $response );
        $decoded     = json_decode( $raw_body, true );

        // If the server returns a non-200 status or invalid JSON, handle it.
        if ( $status_code < 200 || $status_code >= 300 ) {
            $error_message = is_array( $decoded ) && isset( $decoded['message'] ) 
                ? $decoded['message'] 
                : 'API request failed with status code ' . $status_code;

            return [
                'success' => false,
                'data'    => is_array( $decoded ) ? $decoded : null,
                'error'   => $error_message,
            ];
        }

        // Successful response.
        return [
            'success' => true,
            'data'    => is_array( $decoded ) ? $decoded : null,
            'error'   => null,
        ];
    }

    /**
     * Recursively sort an array by its keys to ensure deterministic JSON encoding.
     *
     * @param array $array The array to sort.
     *
     * @return array The sorted array.
     */
    private function sort_array_recursive( array $array ): array {
        ksort( $array );

        foreach ( $array as $key => $value ) {
            if ( is_array( $value ) ) {
                $array[ $key ] = $this->sort_array_recursive( $value );
            }
        }

        return $array;
    }
}