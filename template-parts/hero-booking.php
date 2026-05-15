<?php
/**
 * Shared hero booking widget.
 *
 * Used by the homepage and booking page so the Salon widget keeps the same
 * markup and therefore the same button/footer behavior everywhere.
 */

if (!defined('ABSPATH')) {
    exit;
}

$ba_render_callback = $args['render_callback'] ?? null;
?>

<div id="reservation" class="hero-booking">
    <?php
    if (is_callable($ba_render_callback)) {
        $ba_render_callback();
    } else {
        ba_v201_render_salon_shortcode();
    }
    ?>
</div>
