<?php
/**
 * Feature Manager
 *
 * The single entitlement gateway for the plugin. 
 * Modules and Admin controllers call FeatureManager::isEnabled() to check 
 * feature availability. They never touch LicenseController or the SDK directly.
 *
 * @package SICSAP\Engine
 */

declare(strict_types=1);

namespace SICSAP\Engine;

defined( 'ABSPATH' ) || exit;

use SICSAP\Core\Container;

/**
 * Class FeatureManager
 *
 * Gates access to premium features based on the current license state.
 */
class FeatureManager {

    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private Container $container;

    /**
     * Map of feature slugs to their corresponding LicenseState entitlement keys.
     *
     * @var array<string, string>
     */
    private array $feature_map = [];

    /**
     * List of basic features that are always enabled regardless of license.
     *
     * @var string[]
     */
    private array $basic_features = [];

    /**
     * Constructor.
     *
     * @param Container $container The DI container.
     */
    public function __construct( Container $container ) {
        $this->container = $container;
        $this->init_feature_map();
        $this->init_basic_features();
    }

    /**
     * Check if a specific feature is enabled for the current site/license.
     *
     * @param string $feature The feature slug to check.
     *
     * @return bool True if enabled, false otherwise.
     */
    public function is_enabled( string $feature ): bool {
        // 1. Basic features are always enabled.
        if ( $this->is_basic_feature( $feature ) ) {
            return true;
        }

        // 2. Unknown features are disabled by default (fail closed).
        if ( ! isset( $this->feature_map[ $feature ] ) ) {
            return false;
        }

        $entitlement_key = $this->feature_map[ $feature ];

        // 3. Lazily resolve LicenseState from the container.
        // This prevents fatal errors during Phase 1 before the Premium layer is booted.
        $license_state_class = 'SICSAP\\Premium\\LicenseState';
        if ( ! $this->container->has( $license_state_class ) ) {
            return false;
        }

        $license_state = $this->container->get( $license_state_class );

        // 4. Check the specific entitlement on the LicenseState object.
        if ( is_object( $license_state ) && method_exists( $license_state, 'get_entitlement' ) ) {
            return (bool) $license_state->get_entitlement( $entitlement_key );
        }

        return false;
    }

    /**
     * Check if a feature is considered a basic (free) feature.
     *
     * @param string $feature The feature slug.
     *
     * @return bool
     */
    private function is_basic_feature( string $feature ): bool {
        return in_array( $feature, $this->basic_features, true );
    }

    /**
     * Initialize the mapping of premium feature slugs to LicenseState entitlement keys.
     *
     * @return void
     */
    private function init_feature_map(): void {
        $this->feature_map = [
            'advanced_frameworks' => 'has_advanced_frameworks',
            'root_cause_tools'    => 'has_root_cause_tools',
            'decision_matrix'     => 'has_decision_matrix',
            'advanced_risk'       => 'has_advanced_risk',
            'cross_case_analysis' => 'has_cross_case_analysis',
            'advanced_findings'   => 'has_advanced_findings',
            'implementation_plan' => 'has_implementation_plan',
            'ai_assistance'       => 'has_ai_assistance',
            'advanced_blocks'     => 'has_advanced_blocks',
            'advanced_blueprints' => 'has_advanced_blueprints',
        ];
    }

    /**
     * Initialize the list of basic features that do not require a license check.
     *
     * @return void
     */
    private function init_basic_features(): void {
        $this->basic_features = [
            'basic_workspace',
            'basic_structure',
            'basic_sources',
            'basic_evidence',
            'timeline',
            'stakeholder_analysis',
        ];
    }
}