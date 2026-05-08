<?php
/**
 * Template for the Assistants/Coiffeurs page (slug: assistants).
 * Auto-applied by WordPress — no need to assign in admin.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

$plugin           = SLN_Plugin::getInstance();
$booking_url_base = get_permalink($plugin->getSettings()->getPayPageId());
$repo             = $plugin->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
$service_repo     = $plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
$all_services     = $service_repo->getAll();
$attendants       = $repo->sortByPos($repo->get([]));
?>

<section class="ba-assistants-page">
    <div class="ba-assistants-page__hero">
        <div class="section-inner">
            <div class="ba-assistants-page__eyebrow"><?php esc_html_e('Notre équipe', 'barber-architecte-v201'); ?></div>
            <h1 class="ba-assistants-page__title"><?php the_title(); ?></h1>
            <p class="ba-assistants-page__subtitle"><?php esc_html_e('Des experts passionnés à votre service. Chaque coupe, chaque soin est une signature.', 'barber-architecte-v201'); ?></p>
        </div>
    </div>

    <div class="ba-assistants-page__grid section-inner">
        <?php if ($attendants) : ?>
        <section class="sln-datashortcode sln-datashortcode--assistants">
            <div class="sln-datalist sln-datalist--styled sln-datalist--3cols">
                <?php foreach ($attendants as $attendant) :
                    if (get_post_status($attendant->getId()) !== 'publish') continue;
                    $thumb    = has_post_thumbnail($attendant->getId())
                        ? get_the_post_thumbnail($attendant->getId(), 'thumbnail')
                        : '';
                    $services = $attendant->getServices() ?: $all_services;
                    $book_url = add_query_arg(
                        ['sln_book_attendant' => $attendant->getId()],
                        $booking_url_base
                    );
                ?>
                <div class="sln-datalist__item">
                    <h3 class="sln-datalist__item__name"><?php echo esc_html($attendant->getName()); ?></h3>
                    <div class="sln-datalist__item__image"><?php echo $thumb; ?></div>
                    <?php if ($attendant->getContent()) : ?>
                    <p class="sln-datalist__item__description"><?php echo wp_kses_post($attendant->getContent()); ?></p>
                    <?php endif; ?>
                    <?php if ($services) : ?>
                    <div class="sln-datalist__item__list">
                        <h5><?php esc_html_e('Spécialités', 'barber-architecte-v201'); ?></h5>
                        <ul>
                            <?php foreach ($services as $service) : ?>
                            <li><?php echo esc_html($service->getTitle()); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="sln-datalist__item__actions">
                        <a href="<?php echo esc_url($book_url); ?>" class="sln-datalist__item__cta">
                            <?php esc_html_e('Réserver', 'barber-architecte-v201'); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="sln-datalist_clearfix"></div>
            </div>
        </section>
        <?php endif; ?>
        <button class="services-toggle" data-toggles=".ba-assistants-page__grid .sln-datalist" aria-expanded="false">
            <span><?php esc_html_e('Voir plus', 'barber-architecte-v201'); ?></span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>

    <div class="ba-assistants-page__cta section-inner">
        <a href="<?php echo esc_url(home_url('/booking/')); ?>" class="btn">
            <?php esc_html_e('Prendre rendez-vous', 'barber-architecte-v201'); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php get_footer(); ?>
