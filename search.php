<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="ba-search-page">
    <div class="section-inner">

        <div class="ba-search-page__header">
            <p class="eyebrow"><?php esc_html_e('Résultats', 'barber-architecte-v201'); ?></p>
            <h1 class="ba-search-page__title">
                <?php
                if (get_search_query()) {
                    printf(
                        /* translators: %s: search term */
                        esc_html__('Recherche : « %s »', 'barber-architecte-v201'),
                        '<span class="ba-search-highlight">' . esc_html(get_search_query()) . '</span>'
                    );
                } else {
                    esc_html_e('Recherche', 'barber-architecte-v201');
                }
                ?>
            </h1>
            <?php if (have_posts()) : ?>
                <p class="ba-search-page__count">
                    <?php
                    global $wp_query;
                    printf(
                        /* translators: %d: number of results */
                        esc_html(_n('%d résultat trouvé', '%d résultats trouvés', $wp_query->found_posts, 'barber-architecte-v201')),
                        esc_html($wp_query->found_posts)
                    );
                    ?>
                </p>
            <?php endif; ?>
        </div>

        <form class="ba-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <?php get_template_part('template-parts/search-form', null, ['placeholder' => __('Nouvelle recherche…', 'barber-architecte-v201')]); ?>
        </form>

        <?php if (have_posts()) : ?>
            <div class="ba-search-results">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="ba-search-card" id="post-<?php the_ID(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <a class="ba-search-card__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                <?php the_post_thumbnail('medium', ['alt' => '']); ?>
                            </a>
                        <?php endif; ?>
                        <div class="ba-search-card__body">
                            <p class="ba-search-card__type"><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? get_post_type()); ?></p>
                            <h2 class="ba-search-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <?php if (get_the_excerpt()) : ?>
                                <p class="ba-search-card__excerpt"><?php the_excerpt(); ?></p>
                            <?php endif; ?>
                            <a class="ba-search-card__link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('Lire la suite', 'barber-architecte-v201'); ?>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="ba-search-pagination">
                <?php
                the_posts_pagination([
                    'mid_size'           => 2,
                    'prev_text'          => '← ' . __('Précédent', 'barber-architecte-v201'),
                    'next_text'          => __('Suivant', 'barber-architecte-v201') . ' →',
                    'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'barber-architecte-v201') . ' </span>',
                ]);
                ?>
            </div>

        <?php else : ?>
            <div class="ba-search-empty">
                <div class="ba-search-empty__icon" aria-hidden="true">✂</div>
                <h2 class="ba-search-empty__title"><?php esc_html_e('Aucun résultat', 'barber-architecte-v201'); ?></h2>
                <p class="ba-search-empty__desc"><?php esc_html_e('Votre recherche n\'a donné aucun résultat. Essayez avec d\'autres mots-clés ou revenez à l\'accueil.', 'barber-architecte-v201'); ?></p>
                <a class="btn" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Retour à l\'accueil', 'barber-architecte-v201'); ?></a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
