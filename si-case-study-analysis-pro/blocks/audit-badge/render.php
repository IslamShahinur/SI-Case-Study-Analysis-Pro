<?php
/**
 * Audit Badge Block - Server-side Render
 *
 * @package SI_CSP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_attributes = isset($attributes) ? $attributes : array();
$post_id = isset($block_attributes['postId']) ? intval($block_attributes['postId']) : 0;
$size = isset($block_attributes['size']) ? sanitize_text_field($block_attributes['size']) : 'medium';

if (!$post_id) {
    echo '<div class="si-csp-audit-badge-placeholder">';
    echo '<p>Please select a post to display audit score.</p>';
    echo '</div>';
    return;
}

// Get audit data from post meta
$audit_data = get_post_meta($post_id, '_si_csp_audit_data', true);

if (empty($audit_data) || empty($audit_data['score'])) {
    echo '<div class="si-csp-audit-badge si-csp-no-data">';
    echo '<span>N/A</span>';
    echo '</div>';
    return;
}

$score = intval($audit_data['score']);
$status = '';
$status_class = '';

if ($score >= 90) {
    $status = 'Excellent';
    $status_class = 'excellent';
} elseif ($score >= 75) {
    $status = 'Good';
    $status_class = 'good';
} elseif ($score >= 60) {
    $status = 'Average';
    $status_class = 'average';
} else {
    $status = 'Poor';
    $status_class = 'poor';
}

$size_class = 'si-csp-badge-' . esc_attr($size);
?>

<div class="si-csp-audit-badge-wrapper <?php echo esc_attr($size_class); ?>">
    <div class="si-csp-audit-badge <?php echo esc_attr($status_class); ?>" title="<?php echo esc_attr($status); ?> - Score: <?php echo esc_attr($score); ?>/100">
        <?php echo esc_html($score); ?>
    </div>
    
    <?php if (!empty($audit_data['date'])): ?>
    <div class="si-csp-audit-date">
        Audited: <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($audit_data['date']))); ?>
    </div>
    <?php endif; ?>
</div>
