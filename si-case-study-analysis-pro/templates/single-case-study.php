<?php
/**
 * Template: Single Case Study Display
 *
 * @package SI_CSP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post;
$case_study_data = get_post_meta($post->ID, '_si_csp_case_study_data', true);
$audit_data = get_post_meta($post->ID, '_si_csp_audit_data', true);

get_header();
?>

<div class="si-csp-single-case-study">
    <article id="post-<?php echo esc_attr($post->ID); ?>" <?php post_class('si-csp-case-study-article'); ?>>
        
        <header class="si-csp-entry-header">
            <h1 class="si-csp-entry-title"><?php the_title(); ?></h1>
            
            <?php if (!empty($case_study_data['meta'])): ?>
            <div class="si-csp-case-meta">
                <?php if (!empty($case_study_data['meta']['industry'])): ?>
                <span class="si-csp-meta-industry">
                    <strong><?php esc_html_e('Industry:', 'si-case-study-analysis-pro'); ?></strong>
                    <?php echo esc_html($case_study_data['meta']['industry']); ?>
                </span>
                <?php endif; ?>
                
                <?php if (!empty($case_study_data['meta']['company_size'])): ?>
                <span class="si-csp-meta-size">
                    <strong><?php esc_html_e('Company Size:', 'si-case-study-analysis-pro'); ?></strong>
                    <?php echo esc_html($case_study_data['meta']['company_size']); ?>
                </span>
                <?php endif; ?>
                
                <?php if (!empty($case_study_data['meta']['date'])): ?>
                <span class="si-csp-meta-date">
                    <strong><?php esc_html_e('Date:', 'si-case-study-analysis-pro'); ?></strong>
                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($case_study_data['meta']['date']))); ?>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($audit_data['score'])): ?>
            <div class="si-csp-audit-score-display">
                <span class="si-csp-audit-badge <?php echo ($audit_data['score'] >= 90) ? 'excellent' : (($audit_data['score'] >= 75) ? 'good' : (($audit_data['score'] >= 60) ? 'average' : 'poor')); ?>">
                    <?php echo esc_html($audit_data['score']); ?>
                </span>
                <span class="si-csp-audit-label"><?php esc_html_e('Audit Score', 'si-case-study-analysis-pro'); ?></span>
            </div>
            <?php endif; ?>
        </header>
        
        <div class="si-csp-entry-content">
            <?php if (!empty($case_study_data['challenge'])): ?>
            <section class="si-csp-section si-csp-challenge">
                <h2><?php esc_html_e('Challenge', 'si-case-study-analysis-pro'); ?></h2>
                <div class="si-csp-section-content">
                    <?php echo wp_kses_post($case_study_data['challenge']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($case_study_data['solution'])): ?>
            <section class="si-csp-section si-csp-solution">
                <h2><?php esc_html_e('Solution', 'si-case-study-analysis-pro'); ?></h2>
                <div class="si-csp-section-content">
                    <?php echo wp_kses_post($case_study_data['solution']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($case_study_data['results'])): ?>
            <section class="si-csp-section si-csp-results">
                <h2><?php esc_html_e('Results', 'si-case-study-analysis-pro'); ?></h2>
                <div class="si-csp-section-content">
                    <?php echo wp_kses_post($case_study_data['results']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($case_study_data['tags'])): ?>
            <section class="si-csp-section si-csp-tags">
                <h3><?php esc_html_e('Tags', 'si-case-study-analysis-pro'); ?></h3>
                <div class="si-csp-tag-list">
                    <?php foreach ($case_study_data['tags'] as $tag): ?>
                    <span class="si-csp-tag"><?php echo esc_html($tag); ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>
        
        <footer class="si-csp-entry-footer">
            <?php if (function_exists('the_post_navigation')): ?>
            <div class="si-csp-post-navigation">
                <?php the_post_navigation(array(
                    'prev_text' => '<span class="nav-prev"><span class="nav-label">' . esc_html__('Previous', 'si-case-study-analysis-pro') . '</span><br>%title</span>',
                    'next_text' => '<span class="nav-next"><span class="nav-label">' . esc_html__('Next', 'si-case-study-analysis-pro') . '</span><br>%title</span>',
                )); ?>
            </div>
            <?php endif; ?>
        </footer>
        
    </article>
</div>

<?php
get_footer();
