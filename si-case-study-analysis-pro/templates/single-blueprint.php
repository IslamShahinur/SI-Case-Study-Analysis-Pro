<?php
/**
 * Template: Single Blueprint Display
 *
 * @package SI_CSP
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post;
$blueprint_data = get_post_meta($post->ID, '_si_csp_blueprint_data', true);

get_header();
?>

<div class="si-csp-single-blueprint">
    <article id="post-<?php echo esc_attr($post->ID); ?>" <?php post_class('si-csp-blueprint-article'); ?>>
        
        <header class="si-csp-entry-header">
            <h1 class="si-csp-entry-title"><?php the_title(); ?></h1>
            
            <?php if (!empty($blueprint_data['meta'])): ?>
            <div class="si-csp-blueprint-meta">
                <?php if (!empty($blueprint_data['meta']['version'])): ?>
                <span class="si-csp-meta-version">
                    <strong><?php esc_html_e('Version:', 'si-case-study-analysis-pro'); ?></strong>
                    <?php echo esc_html($blueprint_data['meta']['version']); ?>
                </span>
                <?php endif; ?>
                
                <?php if (!empty($blueprint_data['meta']['date'])): ?>
                <span class="si-csp-meta-date">
                    <strong><?php esc_html_e('Last Updated:', 'si-case-study-analysis-pro'); ?></strong>
                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($blueprint_data['meta']['date']))); ?>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </header>
        
        <div class="si-csp-entry-content">
            <?php if (!empty($blueprint_data['overview'])): ?>
            <section class="si-csp-section si-csp-overview">
                <h2><?php esc_html_e('Overview', 'si-case-study-analysis-pro'); ?></h2>
                <div class="si-csp-section-content">
                    <?php echo wp_kses_post($blueprint_data['overview']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($blueprint_data['requirements'])): ?>
            <section class="si-csp-section si-csp-requirements">
                <h2><?php esc_html_e('Requirements', 'si-case-study-analysis-pro'); ?></h2>
                <ul class="si-csp-requirements-list">
                    <?php foreach ($blueprint_data['requirements'] as $requirement): ?>
                    <li><?php echo wp_kses_post($requirement); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($blueprint_data['steps'])): ?>
            <section class="si-csp-section si-csp-steps">
                <h2><?php esc_html_e('Implementation Steps', 'si-case-study-analysis-pro'); ?></h2>
                <ol class="si-csp-steps-list">
                    <?php foreach ($blueprint_data['steps'] as $index => $step): ?>
                    <li class="si-csp-step-item">
                        <span class="si-csp-step-number"><?php echo esc_html($index + 1); ?></span>
                        <div class="si-csp-step-content"><?php echo wp_kses_post($step); ?></div>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </section>
            <?php endif; ?>
            
            <?php if (!empty($blueprint_data['best_practices'])): ?>
            <section class="si-csp-section si-csp-best-practices">
                <h2><?php esc_html_e('Best Practices', 'si-case-study-analysis-pro'); ?></h2>
                <div class="si-csp-section-content">
                    <?php echo wp_kses_post($blueprint_data['best_practices']); ?>
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
