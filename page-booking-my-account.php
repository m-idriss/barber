<?php
/**
 * Template for the Booking My Account page (slug: booking-my-account).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="content-page">
    <div class="section-inner content-area">
        <div class="ba-account-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <a href="<?php echo esc_url(wp_logout_url(home_url('/login/'))); ?>" class="ba-logout-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <?php esc_html_e('Déconnexion', 'barber-architecte-v201'); ?>
            </a>
        </div>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; endif; ?>
    </div>
</section>

<?php get_footer(); ?>
