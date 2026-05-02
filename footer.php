<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <span>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></span>
        <span><?php esc_html_e('Coupes, barbe et reservations en ligne.', 'barber-architecte-v201'); ?></span>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
