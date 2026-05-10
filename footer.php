<?php
if (!defined('ABSPATH')) {
    exit;
}

$ba_contact = ba_v201_contact_settings();
?>
</main>
<footer class="site-footer premium">
    <!-- Premium CTA Section -->
    <div class="footer-premium-cta">
        <div class="footer-cta-bg"></div>
        <div class="section-inner">
            <div class="footer-cta-wrapper">
                <div class="footer-cta-text">
                    <p class="footer-cta-label"><?php esc_html_e('Expérience exceptionnelle', 'barber-architecte-v201'); ?></p>
                    <h2 class="footer-cta-title"><?php esc_html_e('Prêt à transformer votre look ?', 'barber-architecte-v201'); ?></h2>
                </div>
                <a href="<?php echo esc_url(home_url('/booking')); ?>" class="footer-cta-btn">
                    <span><?php esc_html_e('Réserver une consultation', 'barber-architecte-v201'); ?></span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="site-footer__content premium">
        <div class="section-inner">
            <!-- Top Divider -->
            <div class="footer-divider premium"></div>

            <div class="footer-premium-grid">
                <!-- Column 1: Brand & About -->
                <div class="footer-col footer-col--brand">
                    <div class="footer-brand-box">
                        <a class="footer-brand premium" href="<?php echo esc_url(home_url('/')); ?>">
                            <?php
                            if (ba_v201_logo_id()) {
                                ba_v201_render_logo(['class' => 'footer-brand-logo']);
                            } else {
                                ?>
                                <span class="footer-brand-mark" aria-hidden="true">⊙</span>
                                <?php
                            }
                            ?>
                            <div>
                                <div class="footer-brand-name"><?php bloginfo('name'); ?></div>
                                <div class="footer-brand-tagline"><?php esc_html_e('Barbering Excellence', 'barber-architecte-v201'); ?></div>
                            </div>
                        </a>
                    </div>
                    <p class="footer-brand-description"><?php esc_html_e('Un lieu d\'exception dédié à l\'art du barbering moderne. Expertise, précision et raffinement.', 'barber-architecte-v201'); ?></p>

                    <!-- Premium Social Links -->
                    <?php get_template_part('template-parts/social-links', null, ['wrapper_class' => 'footer-socials premium', 'link_class' => 'footer-social-link premium']); ?>
                </div>

                <!-- Column 2: Services & Excellence -->
                <div class="footer-col footer-col--services">
                    <h3 class="footer-col-title premium">
                        <span class="title-accent">✂</span>
                        <?php esc_html_e('Nos Services', 'barber-architecte-v201'); ?>
                    </h3>
                    <ul class="footer-links premium">
                        <li><a href="#services"><?php esc_html_e('Coupes Gentleman', 'barber-architecte-v201'); ?></a></li>
                        <li><a href="#services"><?php esc_html_e('Soins Barbe Premium', 'barber-architecte-v201'); ?></a></li>
                        <li><a href="#services"><?php esc_html_e('Rasage à Ciel Ouvert', 'barber-architecte-v201'); ?></a></li>
                        <li><a href="#services"><?php esc_html_e('Coloration & Traitements', 'barber-architecte-v201'); ?></a></li>
                        <li><a href="#services"><?php esc_html_e('Massages & Détente', 'barber-architecte-v201'); ?></a></li>
                    </ul>

                    <!-- Badge -->
                    <div class="footer-badge">
                        <span class="badge-dot"></span>
                        <span><?php esc_html_e('Certifié Excellence', 'barber-architecte-v201'); ?></span>
                    </div>
                </div>

                <!-- Column 3: Horaires Élégants -->
                <div class="footer-col footer-col--hours">
                    <h3 class="footer-col-title premium">
                        <span class="title-accent">🕐</span>
                        <?php esc_html_e('Horaires', 'barber-architecte-v201'); ?>
                    </h3>
                    <?php echo ba_v201_render_footer_hours(); ?>
                </div>

                <!-- Column 4: Contact Premium -->
                <div class="footer-col footer-col--contact">
                    <h3 class="footer-col-title premium">
                        <span class="title-accent">✉</span>
                        <?php esc_html_e('Nous Rencontrer', 'barber-architecte-v201'); ?>
                    </h3>
                    <div class="contact-item premium">
                        <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <div>
                            <span class="contact-label"><?php esc_html_e('Téléphone', 'barber-architecte-v201'); ?></span>
                            <a href="tel:<?php echo esc_attr($ba_contact['phone']); ?>"><?php echo esc_html($ba_contact['phone_display']); ?></a>
                        </div>
                    </div>

                    <div class="contact-item premium">
                        <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <div>
                            <span class="contact-label"><?php esc_html_e('Email', 'barber-architecte-v201'); ?></span>
                            <a href="mailto:<?php echo esc_attr($ba_contact['email']); ?>"><?php echo esc_html($ba_contact['email']); ?></a>
                        </div>
                    </div>

                    <div class="contact-item premium">
                        <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div>
                            <span class="contact-label"><?php esc_html_e('Adresse', 'barber-architecte-v201'); ?></span>
                            <a href="<?php echo esc_url($ba_contact['maps_url']); ?>" target="_blank" rel="noopener noreferrer">
                                <address><?php echo esc_html($ba_contact['address_line1']); ?><br><?php echo esc_html($ba_contact['address_line2']); ?></address>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Divider -->
            <div class="footer-divider premium"></div>
        </div>
    </div>

    <!-- Premium Footer Bottom -->
    <div class="site-footer__bottom premium">
        <div class="section-inner">
            <div class="footer-bottom-grid">
                <p class="footer-copyright">
                    &copy; <?php echo esc_html(wp_date('Y')); ?> <span class="highlight"><?php bloginfo('name'); ?></span>
                    <span class="separator">•</span>
                    <span><?php esc_html_e('Barbering Excellence Depuis 2024', 'barber-architecte-v201'); ?></span>
                </p>

                <div class="footer-legal-links">
                    <a href="<?php echo esc_url(home_url('/mentions-legales')); ?>"><?php esc_html_e('Mentions légales', 'barber-architecte-v201'); ?></a>
                    <a href="<?php echo esc_url(home_url('/politique-confidentialite')); ?>"><?php esc_html_e('Confidentialité', 'barber-architecte-v201'); ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
