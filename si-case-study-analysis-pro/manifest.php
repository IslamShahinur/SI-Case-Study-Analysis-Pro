<?php
/**
 * Plugin Manifest
 *
 * Contains plugin metadata, module registry, and feature flags.
 * This file is consumed by Core/Requirements.php for environment checks
 * and by Core/Manifest.php for runtime access to plugin data.
 *
 * @package SICSAP
 */

declare(strict_types=1);

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return the plugin manifest data as an array.
 *
 * @return array{
 *     plugin_name: string,
 *     slug: string,
 *     version: string,
 *     text_domain: string,
 *     min_php: string,
 *     min_wp: string,
 *     modules: string[]
 * }
 */
return [
    'plugin_name' => 'SI Case Study Analysis Pro',
    'slug'        => 'si-case-study-analysis-pro',
    'version'     => '1.0.0',
    'text_domain' => 'si-csap',
    'min_php'     => '7.4',
    'min_wp'      => '5.8',

    /**
     * Module registry.
     * EngineManager will use this list to register modules lazily.
     * Order here does not strictly dictate load order, but groups them logically.
     */
    'modules'     => [
        'Cases',
        'Context',
        'Timeline',
        'Problems',
        'Stakeholders',
        'Sources',
        'Evidence',
        'Claims',
        'Data',
        'Extraction',
        'Frameworks',
        'RootCause',
        'Alternatives',
        'Decisions',
        'Risks',
        'Recommendations',
        'Implementation',
        'Outcomes',
        'Findings',
        'Lessons',
        'Publishing',
    ],
];