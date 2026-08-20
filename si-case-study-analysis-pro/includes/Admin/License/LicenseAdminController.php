<?php
/**
 * License Admin Controller
 *
 * Registers the License admin screen and handles form submissions
 * for activating, deactivating, and verifying the license.
 * Relies strictly on LicenseController for SDK operations.
 *
 * @package SICSAP\Admin\License
 */

declare(strict_types=1);

namespace SICSAP\Admin\License;

defined( 'ABSPATH' ) || exit;

use SICSAP\Core\Container;
use SICSAP\Premium\LicenseController;

/**
 * Class LicenseAdminController
 *
 * Manages the License admin screen UI and form processing.
 */
class LicenseAdminController {

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * The parent menu slug.
     *
     * @var string
     */
    private string $parent_slug = 'si-csap';

    /**
     * This screen's unique slug.
     *
     * @var string
     */
    private string $menu_slug = 'si-csap-license';

    /**
     * Constructor.
     *
     * @param Container $container The DI container.
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Initialize hooks for the admin screen.
     *
     * @return void
     */
    public function init(): void {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        
        // Register form handlers.
        add_action( 'admin_post_si_csap_activate_license', [ $this, 'handle_activate' ] );
        add_action( 'admin_post_si_csap_deactivate_license', [ $this, 'handle_deactivate' ] );
        add_action( 'admin_post_si_csap_verify_license', [ $this, 'handle_verify' ] );
    }

    /**
     * Register the License submenu page.
     *
     * @return void
     */
    public function register_menu(): void {
        add_submenu_page(
            $this->parent_slug,
            __( 'License', 'si-csap' ),
            __( 'License', 'si-csap' ),
            'manage_options',
            $this->menu_slug,
            [ $this, 'render_page' ]
        );
    }

    /**
     * Handle license activation form submission.
     *
     * @return void
     */
    public function handle_activate(): void {
        $this->verify_request( 'si_csap_activate_license_nonce' );

        $license_key = isset( $_POST['si_csap_license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['si_csap_license_key'] ) ) : '';
        
        if ( empty( $license_key ) ) {
            $this->redirect_with_message( 'error', __( 'Please enter a valid license key.', 'si-csap' ) );
        }

        $controller = $this->get_license_controller();
        $result     = $controller->activate_license( $license_key );

        $type    = $result['success'] ? 'success' : 'error';
        $message = $result['message'];

        $this->redirect_with_message( $type, $message );
    }

    /**
     * Handle license deactivation form submission.
     *
     * @return void
     */
    public function handle_deactivate(): void {
        $this->verify_request( 'si_csap_deactivate_license_nonce' );

        $controller = $this->get_license_controller();
        $result     = $controller->deactivate_license();

        $type    = $result['success'] ? 'success' : 'error';
        $message = $result['message'];

        $this->redirect_with_message( $type, $message );
    }

    /**
     * Handle license verification form submission.
     *
     * @return void
     */
    public function handle_verify(): void {
        $this->verify_request( 'si_csap_verify_license_nonce' );

        $force_check = isset( $_POST['si_csap_force_check'] ) && '1' === $_POST['si_csap_force_check'];

        $controller = $this->get_license_controller();
        $result     = $controller->verify_license( $force_check );

        $type    = $result['success'] ? 'success' : 'error';
        $message = $result['message'];

        $this->redirect_with_message( $type, $message );
    }

    /**
     * Render the License admin page.
     *
     * @return void
     */
    public function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $controller = $this->get_license_controller();
        $details    = $controller->get_license_details();

        // Display admin notices if redirected with a message.
        $this->render_admin_notices();

        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'License Management', 'si-csap' ); ?></h1>
            
            <div class="si-csap-license-card" style="background: #fff; border: 1px solid #ccd0d4; padding: 20px; margin-top: 20px; max-width: 800px;">
                
                <h2><?php echo esc_html__( 'Current Status', 'si-csap' ); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Status', 'si-csap' ); ?></th>
                        <td>
                            <strong style="color: <?php echo 'active' === $details['status'] ? '#00a32a' : '#d63638'; ?>;">
                                <?php echo esc_html( ucfirst( $details['status'] ) ); ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Licensed Email', 'si-csap' ); ?></th>
                        <td><?php echo esc_html( $details['email'] ?: '—' ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Expiration', 'si-csap' ); ?></th>
                        <td><?php echo esc_html( $details['expires'] ?: '—' ); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__( 'Active Key', 'si-csap' ); ?></th>
                        <td>
                            <code><?php echo esc_html( $this->mask_license_key( $details['key'] ) ); ?></code>
                        </td>
                    </tr>
                </table>

                <hr>

                <?php if ( 'active' !== $details['status'] || empty( $details['key'] ) ) : ?>
                    <h2><?php echo esc_html__( 'Activate License', 'si-csap' ); ?></h2>
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="si_csap_activate_license">
                        <?php wp_nonce_field( 'si_csap_activate_license_nonce', 'si_csap_license_nonce' ); ?>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="si_csap_license_key"><?php echo esc_html__( 'License Key', 'si-csap' ); ?></label></th>
                                <td>
                                    <input type="text" id="si_csap_license_key" name="si_csap_license_key" class="regular-text" required>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button( __( 'Activate', 'si-csap' ), 'primary', 'submit', false ); ?>
                    </form>
                <?php else : ?>
                    <h2><?php echo esc_html__( 'Manage Active License', 'si-csap' ); ?></h2>
                    <p><?php echo esc_html__( 'Your license is currently active. You can verify its status with the server or deactivate it.', 'si-csap' ); ?></p>
                    
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block; margin-right: 10px;">
                        <input type="hidden" name="action" value="si_csap_verify_license">
                        <input type="hidden" name="si_csap_force_check" value="1">
                        <?php wp_nonce_field( 'si_csap_verify_license_nonce', 'si_csap_license_nonce' ); ?>
                        <?php submit_button( __( 'Verify License', 'si-csap' ), 'secondary', 'submit', false ); ?>
                    </form>

                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block;">
                        <input type="hidden" name="action" value="si_csap_deactivate_license">
                        <?php wp_nonce_field( 'si_csap_deactivate_license_nonce', 'si_csap_license_nonce' ); ?>
                        <?php submit_button( __( 'Deactivate License', 'si-csap' ), 'delete', 'submit', false ); ?>
                    </form>
                <?php endif; ?>

            </div>
        </div>
        <?php
    }

    /**
     * Get the LicenseController instance from the container.
     *
     * @return LicenseController
     */
    private function get_license_controller(): LicenseController {
        return $this->container->get( LicenseController::class );
    }

    /**
     * Verify the current request has valid nonce and capabilities.
     *
     * @param string $nonce_action The nonce action to verify.
     * @return void
     */
    private function verify_request( string $nonce_action ): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to perform this action.', 'si-csap' ) );
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
        if ( ! isset( $_POST['si_csap_license_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['si_csap_license_nonce'] ) ), $nonce_action ) ) {
            wp_die( esc_html__( 'Security check failed. Please try again.', 'si-csap' ) );
        }
    }

    /**
     * Redirect back to the license page with a status message.
     *
     * @param string $type    The message type (success, error).
     * @param string $message The message text.
     * @return void
     */
    private function redirect_with_message( string $type, string $message ): void {
        $redirect_url = add_query_arg(
            [
                'page'             => $this->menu_slug,
                'si_csap_msg_type' => rawurlencode( $type ),
                'si_csap_msg'      => rawurlencode( $message ),
            ],
            admin_url( 'admin.php' )
        );

        wp_safe_redirect( $redirect_url );
        exit;
    }

    /**
     * Render admin notices based on URL parameters.
     *
     * @return void
     */
    private function render_admin_notices(): void {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['si_csap_msg'] ) && isset( $_GET['si_csap_msg_type'] ) ) {
            $type    = sanitize_key( rawurldecode( wp_unslash( $_GET['si_csap_msg_type'] ) ) );
            $message = sanitize_text_field( rawurldecode( wp_unslash( $_GET['si_csap_msg'] ) ) );
            
            $class = ( 'success' === $type ) ? 'notice-success' : 'notice-error';
            
            echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
        }
    }

    /**
     * Mask a license key for secure display (e.g., XXXX-XXXX-XXXX-1234).
     *
     * @param string $key The license key.
     * @return string
     */
    private function mask_license_key( string $key ): string {
        if ( empty( $key ) || strlen( $key ) < 8 ) {
            return '—';
        }
        
        $length = strlen( $key );
        $visible = substr( $key, -4 );
        $masked = str_repeat( '*', $length - 4 );
        
        return $masked . $visible;
    }
}