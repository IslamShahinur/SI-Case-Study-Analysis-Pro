<?php
/**
 * Case Study Card Block - Server-side Render
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
$show_meta = isset($block_attributes['showMeta']) ? (bool)$block_attributes['showMeta'] : true;
$show_tags = isset($block_attributes['showTags']) ? (bool)$block_attributes['showTags'] : true;

if (!$post_id) {
    echo '<div class="si-csp-case-study-card si-csp-placeholder">';
    echo '<p>Please select a case study to display.</p>';
    echo '</div>';
    return;
}

// Get extracted data from post meta
$case_study_data = get_post_meta($post_id, '_si_csp_case_study_data', true);

if (empty($case_study_data)) {
    echo '<div class="si-csp-case-study-card si-csp-no-data">';
    echo '<p>Case study data not available. Please run data extraction.</p>';
    echo '</div>';
    return;
}

$card_class = 'si-csp-case-study-card si-csp-style-' . esc_attr($display_style);
?>

<div class="<?php echo esc_attr($card_class); ?>">
    <div class="si-csp-card-header">
        <h3 class="si-csp-card-title">
            <?php echo esc_html(get_the_title($post_id)); ?>
        </h3>
        
        <?php if ($show_meta && !empty($case_study_data['meta'])): ?>
        <div class="si-csp-card-meta">
            <?php if (!empty($case_study_data['meta']['industry'])): ?>
            <span class="si-csp-meta-item">
                <span class="si-csp-meta-label">Industry:</span>
                <?php echo esc_html($case_study_data['meta']['industry']); ?>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($case_study_data['meta']['company_size'])): ?>
            <span class="si-csp-meta-item">
                <span class="si-csp-meta-label">Company Size:</span>
                <?php echo esc_html($case_study_data['meta']['company_size']); ?>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($case_study_data['meta']['date'])): ?>
            <span class="si-csp-meta-item">
                <span class="si-csp-meta-label">Date:</span>
                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($case_study_data['meta']['date']))); ?>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="si-csp-card-content">
        <?php if (!empty($case_study_data['challenge'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Challenge</h4>
            <p><?php echo wp_kses_post($case_study_data['challenge']); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($case_study_data['solution'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Solution</h4>
            <p><?php echo wp_kses_post($case_study_data['solution']); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($case_study_data['results'])): ?>
        <div class="si-csp-card-section">
            <h4 class="si-csp-card-section-title">Results</h4>
            <p><?php echo wp_kses_post($case_study_data['results']); ?></p>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="si-csp-card-footer">
        <?php if ($show_tags && !empty($case_study_data['tags'])): ?>
        <div class="si-csp-card-tags">
            <?php foreach ($case_study_data['tags'] as $tag): ?>
            <span class="si-csp-tag"><?php echo esc_html($tag); ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="si-csp-card-action">
            Read Full Case Study
        </a>
    </div>
</div>
