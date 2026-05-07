<?php
/**
 * Template Name: Page de connexion
 *
 * Handles login form and logout confirmation.
 * Assign this template to a WordPress page (e.g. slug: "connexion").
 */

if (!defined('ABSPATH')) {
    exit;
}

// Redirect already-logged-in users away (except on logout confirmation)
$is_logged_out = isset($_GET['loggedout']) && $_GET['loggedout'] === 'true';
if (is_user_logged_in() && !$is_logged_out) {
    wp_redirect(home_url('/'));
    exit;
}

// Handle login form submission errors via WP redirect
$login_error = '';
if (isset($_GET['login']) && $_GET['login'] === 'failed') {
    $login_error = __('Identifiant ou mot de passe incorrect. Veuillez réessayer.', 'barber-architecte-v201');
}

get_header();
?>

<section class="ba-login-page">
    <div class="ba-login-page__inner">

        <?php if ($is_logged_out) : ?>
            <!-- Logout confirmation -->
            <div class="ba-login-card">
                <div class="ba-login-card__icon" aria-hidden="true">✂</div>
                <h1 class="ba-login-card__title"><?php esc_html_e('À bientôt !', 'barber-architecte-v201'); ?></h1>
                <p class="ba-login-card__subtitle"><?php esc_html_e('Vous avez été déconnecté avec succès.', 'barber-architecte-v201'); ?></p>
                <div class="ba-login-card__actions">
                    <a class="btn" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Se reconnecter', 'barber-architecte-v201'); ?></a>
                    <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Retour à l\'accueil', 'barber-architecte-v201'); ?></a>
                </div>
            </div>

        <?php else : ?>
            <!-- Login form -->
            <div class="ba-login-card">
                <div class="ba-login-card__icon" aria-hidden="true">✂</div>
                <h1 class="ba-login-card__title"><?php esc_html_e('Connexion', 'barber-architecte-v201'); ?></h1>
                <p class="ba-login-card__subtitle"><?php esc_html_e('Accédez à votre espace personnel.', 'barber-architecte-v201'); ?></p>

                <?php if ($login_error) : ?>
                    <div class="ba-login-error" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                        </svg>
                        <?php echo esc_html($login_error); ?>
                    </div>
                <?php endif; ?>

                <?php
                wp_login_form([
                    'redirect'       => home_url('/'),
                    'form_id'        => 'ba-login-form',
                    'label_username' => __('Identifiant', 'barber-architecte-v201'),
                    'label_password' => __('Mot de passe', 'barber-architecte-v201'),
                    'label_remember' => __('Se souvenir de moi', 'barber-architecte-v201'),
                    'label_log_in'   => __('Se connecter', 'barber-architecte-v201'),
                    'remember'       => true,
                    'value_remember' => false,
                ]);
                ?>

                <div class="ba-login-card__links">
                    <a href="<?php echo esc_url(wp_lostpassword_url(get_permalink())); ?>"><?php esc_html_e('Mot de passe oublié ?', 'barber-architecte-v201'); ?></a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
