<?php
get_header();
?>
<section class="content-page">
    <div class="section-inner content-area">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <h1 class="page-title"><?php esc_html_e('Aucun contenu', 'barber-architecte-v201'); ?></h1>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
