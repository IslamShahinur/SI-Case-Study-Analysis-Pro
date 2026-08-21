<?php
/**
 * Template: Archive Case Studies
 *
 * @package SI_CSP
 * @since 1.0.0
 */

get_header();
?>

<div class="si-csp-archive si-csp-case-studies-archive">
    <header class="si-csp-archive-header">
        <h1 class="si-csp-archive-title">
            <?php post_type_archive_title(); ?>
        </h1>
        
        <?php if (have_posts() && $wp_query->found_posts > 0): ?>
        <p class="si-csp-archive-count">
            <?php printf(esc_html(_n('%d case study found', '%d case studies found', $wp_query->found_posts, 'si-case-study-analysis-pro')), $wp_query->found_posts); ?>
        </p>
        <?php endif; ?>
    </header>
    
    <?php if (have_posts()): ?>
    <div class="si-csp-archive-grid">
        <?php while (have_posts()): the_post(); ?>
        
        <?php
        $case_study_data = get_post_meta(get_the_ID(), '_si_csp_case_study_data', true);
        $audit_data = get_post_meta(get_the_ID(), '_si_csp_audit_data', true);
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('si-csp-archive-card'); ?>>
            <div class="si-csp-card-inner">
                <header class="si-csp-card-header">
                    <h2 class="si-csp-card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <?php if (!empty($audit_data['score'])): ?>
                    <div class="si-csp-card-audit-badge">
                        <span class="si-csp-audit-badge <?php echo ($audit_data['score'] >= 90) ? 'excellent' : (($audit_data['score'] >= 75) ? 'good' : (($audit_data['score'] >= 60) ? 'average' : 'poor')); ?>">
                            <?php echo esc_html($audit_data['score']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </header>
                
                <div class="si-csp-card-meta">
                    <?php if (!empty($case_study_data['meta']['industry'])): ?>
                    <span class="si-csp-meta-item">
                        <strong><?php esc_html_e('Industry:', 'si-case-study-analysis-pro'); ?></strong>
                        <?php echo esc_html($case_study_data['meta']['industry']); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($case_study_data['meta']['company_size'])): ?>
                    <span class="si-csp-meta-item">
                        <strong><?php esc_html_e('Size:', 'si-case-study-analysis-pro'); ?></strong>
                        <?php echo esc_html($case_study_data['meta']['company_size']); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="si-csp-card-excerpt">
                    <?php if (!empty($case_study_data['challenge'])): ?>
                    <div class="si-csp-challenge-preview">
                        <strong><?php esc_html_e('Challenge:', 'si-case-study-analysis-pro'); ?></strong>
                        <?php echo wp_trim_words(wp_strip_all_tags($case_study_data['challenge']), 15, '...'); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($case_study_data['tags'])): ?>
                <div class="si-csp-card-tags">
                    <?php foreach (array_slice($case_study_data['tags'], 0, 3) as $tag): ?>
                    <span class="si-csp-tag"><?php echo esc_html($tag); ?></span>
                    <?php endforeach; ?>
                    <?php if (count($case_study_data['tags']) > 3): ?>
                    <span class="si-csp-tag-more">+<?php echo esc_html(count($case_study_data['tags']) - 3); ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <footer class="si-csp-card-footer">
                    <a href="<?php the_permalink(); ?>" class="si-csp-card-action">
                        <?php esc_html_e('Read More', 'si-case-study-analysis-pro'); ?>
                    </a>
                </footer>
            </div>
        </article>
        
        <?php endwhile; ?>
    </div>
    
    <?php
    the_posts_pagination(array(
        'mid_size' => 2,
        'prev_text' => esc_html__('Previous', 'si-case-study-analysis-pro'),
        'next_text' => esc_html__('Next', 'si-case-study-analysis-pro'),
    ));
    ?>
    
    <?php else: ?>
    <div class="si-csp-no-results">
        <p><?php esc_html_e('No case studies found.', 'si-case-study-analysis-pro'); ?></p>
    </div>
    <?php endif; ?>
</div>

<?php
get_footer();
