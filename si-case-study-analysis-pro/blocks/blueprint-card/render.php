<?php
/**
 * Blueprint Card Block - Server-side Render
 *
 * @package SI_CSP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_attributes = isset($attributes) ? $attributes : array();
$post_id = isset($block_attributes['postId']) ? intval($block_attributes['postId']) : 0;
$display_style = isset($block_attributes['displayStyle']) ? sanitize_text_field($block_attributes['displayStyle']) : 'default';

if (!$post_id) {
    echo '<div class="si-csp-blueprint-card si-csp-placeholder">';
    echo '<p>Please select a blueprint to display.</p>';
    echo '</div>';
    return;
}

// Get extracted data from post meta
$blueprint_data = get_post_meta($post_id, '_si_csp_blueprint_data', true);

if (empty($blueprint_data)) {
    echo '<div class="si-csp-blueprint-card si-csp-no-data">';
    echo '<p>Blueprint data not available. Please run data extraction.</p>';
    echo '</div>';
    return;
}

$card_class = 'si-csp-blueprint-card si-csp-style-' . esc_attr($display_style);
?>

<div class="<?php echo esc_attr($card_class); ?>">
    <div class="si-csp-card-header">
        <h3 class="si-csp-card-title">
            <?php echo esc_html(get_the_title($post_id)); ?>
        </h3>
        
        <?php if (!empty($blueprint_data['meta']['version'])): ?>
        <div class="si-csp-card-meta">
            <span class="si-csp-meta-item">
                <span class="si-csp-meta-label">Version:</span>
                <?php echo esc_html($blueprint_data['meta']['version']); ?>
            </span>
            
            <?php if (!empty($blueprint_data['meta']['date'])): ?>
            <span class="si-csp-meta-item">
                <span class="si-csp-meta-label">Last Updated:</span>
                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($blueprint_data['meta']['date']))); ?>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="si-csp-card-content">
        <?php if (!empty($blueprint_data['overview'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Overview</h4>
            <p><?php echo wp_kses_post($blueprint_data['overview']); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($blueprint_data['requirements'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Requirements</h4>
            <ul>
            <?php foreach ($blueprint_data['requirements'] as $requirement): ?>
                <li><?php echo wp_kses_post($requirement); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($blueprint_data['steps'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Implementation Steps</h4>
            <ol>
            <?php foreach ($blueprint_data['steps'] as $step): ?>
                <li><?php echo wp_kses_post($step); ?></li>
            <?php endforeach; ?>
            </ol>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($blueprint_data['best_practices'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Best Practices</h4>
            <p><?php echo wp_kses_post($blueprint_data['best_practices']); ?></p>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="si-csp-card-footer">
        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="si-csp-card-action">
            View Full Blueprint
        </a>
    </div>
</div>
