<?php
if (!defined('ABSPATH')) {
    exit;
}
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
                <a href="/booking" class="footer-cta-btn">
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
                            $footer_logo_id = get_theme_mod('custom_logo');
                            if ($footer_logo_id) {
                                echo wp_get_attachment_image(
                                    $footer_logo_id,
                                    'full',
                                    false,
                                    array(
                                        'class' => 'footer-brand-logo',
                                        'alt'   => get_bloginfo('name'),
                                    )
                                );
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
                    <div class="footer-socials premium">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="footer-social-link premium" title="Facebook" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-link premium" title="Instagram" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.117.6c-.69.263-1.296.577-1.933 1.214-.637.637-.951 1.243-1.214 1.933-.267.788-.468 1.659-.527 3.937C.004 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 2.278.261 3.149.527 3.937.263.69.577 1.296 1.214 1.933.637.637 1.243.951 1.933 1.214.788.267 1.659.468 3.937.527C8.333 23.996 8.74 24 12 24s3.667-.015 4.947-.072c2.278-.06 3.149-.261 3.937-.527.69-.263 1.296-.577 1.933-1.214.637-.637.951-1.243 1.214-1.933.267-.788.468-1.659.527-3.937.058-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-2.278-.261-3.149-.527-3.937-.263-.69-.577-1.296-1.214-1.933C21.319 1.27 20.713.957 20.023.694c-.788-.267-1.659-.468-3.937-.527C15.667.004 15.26 0 12 0zm0 2.16c3.203 0 3.585.009 4.849.070 1.171.054 1.805.244 2.227.408.56.217.96.477 1.382.896.419.42.679.822.896 1.381.164.422.354 1.057.408 2.227.061 1.264.07 1.646.07 4.849s-.009 3.585-.07 4.849c-.054 1.171-.244 1.805-.408 2.227-.217.56-.477.96-.896 1.382-.42.419-.822.679-1.381.896-.422.164-1.057.354-2.227.408-1.264.061-1.646.07-4.849.07s-3.585-.009-4.849-.07c-1.171-.054-1.805-.244-2.227-.408-.56-.217-.96-.477-1.382-.896-.419-.42-.679-.822-.896-1.381-.164-.422-.354-1.057-.408-2.227-.061-1.264-.07-1.646-.07-4.849s.009-3.585.07-4.849c.054-1.171.244-1.805.408-2.227.217-.56.477-.96.896-1.382.42-.419.822-.679 1.381-.896.422-.164 1.057-.354 2.227-.408 1.264-.061 1.646-.07 4.849-.07z"/></svg>
                        </a>
                        <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="footer-social-link premium" title="TikTok" aria-label="TikTok">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.498 3.75c1.73 0 3.248.755 4.276 1.934.706-.135 1.413-.302 2.113-.495v3.975c-.594.092-1.176.27-1.733.524 1.025 1.043 1.655 2.481 1.655 4.084 0 3.314-2.686 6-6 6a5.993 5.993 0 01-4.873-2.43c-1.247.996-2.84 1.597-4.582 1.597-3.865 0-7-3.135-7-7 0-3.762 2.955-6.834 6.628-6.99.116-1.255.632-2.388 1.387-3.305C6.256 1.055 5.27.744 4.25.744 2.188.744.5 2.432.5 4.494v14.012c0 2.063 1.688 3.75 3.75 3.75.949 0 1.817-.357 2.475-.939 1.09 1.038 2.59 1.689 4.275 1.689 3.314 0 6-2.686 6-6 0-.656-.106-1.286-.3-1.885.68-.598 1.51-.92 2.297-.92.829 0 1.593.267 2.196.706v-5.06c-.48.104-.988.16-1.498.16z"/></svg>
                        </a>
                    </div>
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
                    <div class="footer-hours-premium">
                        <div class="hour-row">
                            <span class="hour-label"><?php esc_html_e('Lun • Mar • Mer', 'barber-architecte-v201'); ?></span>
                            <span class="hour-value">09:00 <span class="hour-dash">–</span> 19:00</span>
                        </div>
                        <div class="hour-row">
                            <span class="hour-label"><?php esc_html_e('Jeu • Ven', 'barber-architecte-v201'); ?></span>
                            <span class="hour-value">09:00 <span class="hour-dash">–</span> 20:00</span>
                        </div>
                        <div class="hour-row">
                            <span class="hour-label"><?php esc_html_e('Samedi', 'barber-architecte-v201'); ?></span>
                            <span class="hour-value">10:00 <span class="hour-dash">–</span> 18:00</span>
                        </div>
                        <div class="hour-row closed">
                            <span class="hour-label"><?php esc_html_e('Dimanche', 'barber-architecte-v201'); ?></span>
                            <span class="hour-value"><?php esc_html_e('Repos', 'barber-architecte-v201'); ?></span>
                        </div>
                    </div>
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
                            <a href="tel:+33123456789">+33 1 23 45 67 89</a>
                        </div>
                    </div>

                    <div class="contact-item premium">
                        <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <div>
                            <span class="contact-label"><?php esc_html_e('Email', 'barber-architecte-v201'); ?></span>
                            <a href="mailto:contact@barberlarchitecte.com">contact@barberlarchitecte.com</a>
                        </div>
                    </div>

                    <div class="contact-item premium">
                        <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <div>
                            <span class="contact-label"><?php esc_html_e('Adresse', 'barber-architecte-v201'); ?></span>
                            <address>123 Rue de la Coupe<br>75000 Paris</address>
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
                    &copy; <?php echo esc_html(date('Y')); ?> <span class="highlight"><?php bloginfo('name'); ?></span>
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
