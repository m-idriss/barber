<?php
if (!defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top Bar Premium -->
<?php $ba_status = ba_v201_current_status(); ?>
<div class="topbar">
    <div class="topbar__inner">
        <div class="topbar__left">
            <span class="topbar__status <?php echo $ba_status['is_open'] ? 'is-open' : 'is-closed'; ?>">
                <span class="topbar__dot"></span>
                <?php echo esc_html($ba_status['label']); ?>
            </span>
        </div>
        <div class="topbar__right">
            <a href="tel:+33123456789" class="topbar__link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                +33 1 23 45 67 89
            </a>
            <span class="topbar__separator">·</span>
            <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="topbar__link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?php esc_html_e('Paris', 'barber-architecte-v201'); ?>
            </a>
        </div>
    </div>
</div>

<header class="site-header" id="siteHeader">
    <div class="site-header__inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php
            $logo_id = get_theme_mod('custom_logo') ?: ba_v201_attachment_by_file('2026/05/LOGO-DEFINITIF.jpg');
            if ($logo_id) {
                echo wp_get_attachment_image($logo_id, 'full', false, ['alt' => get_bloginfo('name')]);
            }
            ?>
            <div class="brand__text">
                <span class="brand__name"><?php bloginfo('name'); ?></span>
                <span class="brand__tagline"><?php esc_html_e('Barbering Excellence', 'barber-architecte-v201'); ?></span>
            </div>
        </a>

        <nav class="site-nav" aria-label="<?php esc_attr_e('Navigation principale', 'barber-architecte-v201'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
            ]);
            ?>
        </nav>

        <div class="site-header__actions">
            <a class="btn-header" href="#reservation">
                <span><?php esc_html_e('Réserver', 'barber-architecte-v201'); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>

            <button class="nav-burger" id="navBurger" aria-label="<?php esc_attr_e('Ouvrir le menu', 'barber-architecte-v201'); ?>" aria-expanded="false" aria-controls="mobileNav">
                <span class="nav-burger__line"></span>
                <span class="nav-burger__line"></span>
                <span class="nav-burger__line"></span>
            </button>
        </div>
    </div>
</header>

<div class="mobile-nav" id="mobileNav" aria-hidden="true">
    <div class="mobile-nav__backdrop" data-nav-close></div>
    <aside class="mobile-nav__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Menu mobile', 'barber-architecte-v201'); ?>">
        <div class="mobile-nav__header">
            <span class="mobile-nav__brand"><?php bloginfo('name'); ?></span>
            <button class="mobile-nav__close" data-nav-close aria-label="<?php esc_attr_e('Fermer le menu', 'barber-architecte-v201'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="mobile-nav__menu" aria-label="<?php esc_attr_e('Navigation mobile', 'barber-architecte-v201'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
            ]);
            ?>
        </nav>

        <div class="mobile-nav__footer">
            <a class="mobile-nav__cta" href="#reservation"><?php esc_html_e('Réserver maintenant', 'barber-architecte-v201'); ?></a>
            <div class="mobile-nav__socials">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.117.6c-.69.263-1.296.577-1.933 1.214-.637.637-.951 1.243-1.214 1.933-.267.788-.468 1.659-.527 3.937C.004 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 2.278.261 3.149.527 3.937.263.69.577 1.296 1.214 1.933.637.637 1.243.951 1.933 1.214.788.267 1.659.468 3.937.527C8.333 23.996 8.74 24 12 24s3.667-.015 4.947-.072c2.278-.06 3.149-.261 3.937-.527.69-.263 1.296-.577 1.933-1.214.637-.637.951-1.243 1.214-1.933.267-.788.468-1.659.527-3.937.058-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-2.278-.261-3.149-.527-3.937-.263-.69-.577-1.296-1.214-1.933C21.319 1.27 20.713.957 20.023.694c-.788-.267-1.659-.468-3.937-.527C15.667.004 15.26 0 12 0zm0 2.16c3.203 0 3.585.009 4.849.070 1.171.054 1.805.244 2.227.408.56.217.96.477 1.382.896.419.42.679.822.896 1.381.164.422.354 1.057.408 2.227.061 1.264.07 1.646.07 4.849s-.009 3.585-.07 4.849c-.054 1.171-.244 1.805-.408 2.227-.217.56-.477.96-.896 1.382-.42.419-.822.679-1.381.896-.422.164-1.057.354-2.227.408-1.264.061-1.646.07-4.849.07s-3.585-.009-4.849-.07c-1.171-.054-1.805-.244-2.227-.408-.56-.217-.96-.477-1.382-.896-.419-.42-.679-.822-.896-1.381-.164-.422-.354-1.057-.408-2.227-.061-1.264-.07-1.646-.07-4.849s.009-3.585.07-4.849c.054-1.171.244-1.805.408-2.227.217-.56.477-.96.896-1.382.42-.419.822-.679 1.381-.896.422-.164 1.057-.354 2.227-.408 1.264-.061 1.646-.07 4.849-.07z"/></svg>
                </a>
                <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.498 3.75c1.73 0 3.248.755 4.276 1.934.706-.135 1.413-.302 2.113-.495v3.975c-.594.092-1.176.27-1.733.524 1.025 1.043 1.655 2.481 1.655 4.084 0 3.314-2.686 6-6 6a5.993 5.993 0 01-4.873-2.43c-1.247.996-2.84 1.597-4.582 1.597-3.865 0-7-3.135-7-7 0-3.762 2.955-6.834 6.628-6.99.116-1.255.632-2.388 1.387-3.305C6.256 1.055 5.27.744 4.25.744 2.188.744.5 2.432.5 4.494v14.012c0 2.063 1.688 3.75 3.75 3.75.949 0 1.817-.357 2.475-.939 1.09 1.038 2.59 1.689 4.275 1.689 3.314 0 6-2.686 6-6 0-.656-.106-1.286-.3-1.885.68-.598 1.51-.92 2.297-.92.829 0 1.593.267 2.196.706v-5.06c-.48.104-.988.16-1.498.16z"/></svg>
                </a>
            </div>
        </div>
    </aside>
</div>

<script>
(function() {
    const burger = document.getElementById('navBurger');
    const nav = document.getElementById('mobileNav');
    if (!burger || !nav) return;

    const closeButtons = nav.querySelectorAll('[data-nav-close]');
    const menuLinks = nav.querySelectorAll('a');

    function openNav() {
        nav.classList.add('is-open');
        burger.classList.add('is-active');
        burger.setAttribute('aria-expanded', 'true');
        nav.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeNav() {
        nav.classList.remove('is-open');
        burger.classList.remove('is-active');
        burger.setAttribute('aria-expanded', 'false');
        nav.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    burger.addEventListener('click', function() {
        nav.classList.contains('is-open') ? closeNav() : openNav();
    });

    closeButtons.forEach(btn => btn.addEventListener('click', closeNav));
    menuLinks.forEach(link => link.addEventListener('click', closeNav));

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) {
            closeNav();
        }
    });
})();

// Header scroll effect
(function() {
    const header = document.getElementById('siteHeader');
    if (!header) return;

    let lastScroll = 0;
    let ticking = false;

    function updateHeader() {
        const scrollY = window.scrollY;

        if (scrollY > 30) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }

        lastScroll = scrollY;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }, { passive: true });
})();

// Sur mobile, déplace le widget de réservation juste après le titre du hero
(function() {
    const MOBILE_BREAKPOINT = 820;
    const DEBUG = true;

    function log(...args) {
        if (DEBUG) console.log('[HeroReorder]', ...args);
    }

    function repositionBookingWidget() {
        log('Running repositionBookingWidget, width:', window.innerWidth);

        // Cherche le widget Elementor qui contient le booking
        const salonInner = document.querySelector('#sln-salon, .sln-bootstrap');
        log('salonInner found:', !!salonInner, salonInner);
        if (!salonInner) {
            log('No #sln-salon found, retrying in 1s...');
            return false;
        }

        // Le widget Elementor parent contient tout le shortcode
        const widgetWrapper = salonInner.closest('.elementor-widget') || salonInner.closest('.hero-booking') || salonInner.parentElement;
        log('widgetWrapper:', widgetWrapper);
        if (!widgetWrapper) return false;

        // Trouve le titre principal du hero
        const heroTitle = document.querySelector(
            '.ba-el-hero h1, .ba-el-hero .elementor-heading-title, .hero h1, .elementor-section h1, h1.elementor-heading-title'
        );
        log('heroTitle:', heroTitle);
        if (!heroTitle) return false;

        // Trouve le widget Elementor du titre
        const titleWrapper = heroTitle.closest('.elementor-widget') || heroTitle.closest('.elementor-element') || heroTitle;
        log('titleWrapper:', titleWrapper);

        // Mémoriser le parent original du widget
        if (!widgetWrapper.dataset.originalSaved) {
            widgetWrapper.dataset.originalSaved = 'true';
            widgetWrapper._originalParent = widgetWrapper.parentNode;
            widgetWrapper._originalNextSibling = widgetWrapper.nextSibling;
            log('Original position saved');
        }

        const isMobile = window.innerWidth < MOBILE_BREAKPOINT;
        const isMovedToHeader = widgetWrapper.dataset.movedToHeader === 'true';
        log('isMobile:', isMobile, 'isMoved:', isMovedToHeader);

        if (isMobile && !isMovedToHeader) {
            log('-> Moving widget after title');
            titleWrapper.parentNode.insertBefore(widgetWrapper, titleWrapper.nextSibling);
            widgetWrapper.dataset.movedToHeader = 'true';
        } else if (!isMobile && isMovedToHeader) {
            log('-> Restoring widget to original position');
            if (widgetWrapper._originalNextSibling && widgetWrapper._originalNextSibling.parentNode === widgetWrapper._originalParent) {
                widgetWrapper._originalParent.insertBefore(widgetWrapper, widgetWrapper._originalNextSibling);
            } else {
                widgetWrapper._originalParent.appendChild(widgetWrapper);
            }
            widgetWrapper.dataset.movedToHeader = 'false';
        }

        return true;
    }

    // Init avec retry si le widget n'est pas encore prêt
    function init() {
        let attempts = 0;
        function tryReposition() {
            attempts++;
            if (!repositionBookingWidget() && attempts < 10) {
                setTimeout(tryReposition, 500);
            }
        }
        tryReposition();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', repositionBookingWidget);

    // Réagir au resize avec debounce
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(repositionBookingWidget, 150);
    });
})();

// Déplace le bloc Conseil/Précision/Style dans le hero juste après les barbers
(function() {
    const DEBUG = true;
    function log(...args) { if (DEBUG) console.log('[MoveSignature]', ...args); }

    function moveSignatureSection() {
        log('=== Trying to move signature section ===');

        // 1. Trouver les titres Conseil/Précision/Style par leur texte
        const targetTexts = ['conseil', 'précision', 'precision', 'style'];
        const allHeadings = document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title');
        log('Total headings found:', allHeadings.length);

        let foundHeadings = [];
        for (const h of allHeadings) {
            const text = (h.textContent || '').trim().toLowerCase();
            if (targetTexts.includes(text)) {
                foundHeadings.push({ text, element: h });
                log('Found target heading:', text, h);
            }
        }

        if (foundHeadings.length === 0) {
            log('No Conseil/Précision/Style headings found');
            return false;
        }

        // 2. Remonter jusqu'à la section Elementor commune
        const firstHeading = foundHeadings[0].element;
        const signatureSection = firstHeading.closest('.elementor-top-section, .elementor-section, section.elementor-element[data-element_type="section"]');
        log('signatureSection:', signatureSection);

        if (!signatureSection) {
            log('No parent section found');
            return false;
        }

        // 3. Trouver le conteneur des barbers (multiples sélecteurs)
        let barbersContainer =
            document.querySelector('.ba-el-hero-attendants') ||
            document.querySelector('.hero-team') ||
            document.querySelector('[class*="hero-attendant"]') ||
            document.querySelector('.sln-datashortcode--assistants') ||
            document.querySelector('.sln-datalist');

        // Fallback : chercher un widget Elementor qui contient des cartes barbers
        if (!barbersContainer) {
            const sample = document.querySelector('.sln-datalist__item, .sln-datalist__item__image');
            if (sample) {
                barbersContainer = sample.closest('.sln-datalist, .sln-datashortcode--assistants') || sample.parentElement;
            }
        }

        log('barbersContainer:', barbersContainer);

        if (!barbersContainer) {
            log('No barbers container found');
            return false;
        }

        // 4. Trouver le widget parent des barbers OU sa colonne
        // On veut le widget Elementor qui contient le shortcode des barbers
        const barbersAnchor =
            barbersContainer.closest('.elementor-widget') ||
            barbersContainer.closest('.elementor-element') ||
            barbersContainer;
        log('barbersAnchor:', barbersAnchor);

        // 5. Si déjà déplacé, ne rien faire
        if (signatureSection.dataset.movedToHero === 'true') {
            log('Already moved, skipping');
            return true;
        }

        // 6. Vérifier que la section ET les barbers ont des parents dans le DOM
        if (!barbersAnchor.parentNode || !signatureSection.parentNode) {
            log('Missing parent node');
            return false;
        }

        // 7. Déplacer la section juste après le bloc des barbers
        log('-> Moving signature section after barbers');
        barbersAnchor.parentNode.insertBefore(signatureSection, barbersAnchor.nextSibling);
        signatureSection.dataset.movedToHero = 'true';
        log('✓ Done');
        return true;
    }

    function init() {
        let attempts = 0;
        function tryMove() {
            attempts++;
            log('Attempt', attempts);
            const success = moveSignatureSection();
            if (!success && attempts < 15) {
                setTimeout(tryMove, 500);
            }
        }
        tryMove();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', () => setTimeout(moveSignatureSection, 200));
})();

// Annule les styles inline du plugin SLN qui décale le widget de réservation (margins négatifs)
(function() {
    function fixSalonInlineStyles() {
        const salon = document.getElementById('sln-salon');
        if (!salon) return false;

        // Retire les styles inline width, margin-left, margin-right qui sont en !important
        salon.style.removeProperty('width');
        salon.style.removeProperty('margin-left');
        salon.style.removeProperty('margin-right');
        // Si style attribute est vide après cleanup, le supprime complètement
        if (!salon.getAttribute('style') || salon.getAttribute('style').trim() === '') {
            salon.removeAttribute('style');
        }
        return true;
    }

    // Init avec retry
    function init() {
        let attempts = 0;
        function tryFix() {
            attempts++;
            const ok = fixSalonInlineStyles();
            if (!ok && attempts < 20) {
                setTimeout(tryFix, 250);
            }
        }
        tryFix();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', fixSalonInlineStyles);

    // Réagir au resize (le plugin SLN peut recalculer)
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(fixSalonInlineStyles, 100);
    });

    // Observer pour détecter si le plugin SLN remet ses styles
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            for (const m of mutations) {
                if (m.type === 'attributes' && m.attributeName === 'style' && m.target.id === 'sln-salon') {
                    fixSalonInlineStyles();
                }
            }
        });

        // Observer dès qu'on a #sln-salon
        function startObserving() {
            const salon = document.getElementById('sln-salon');
            if (salon) {
                observer.observe(salon, { attributes: true, attributeFilter: ['style'] });
            } else {
                setTimeout(startObserving, 250);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startObserving);
        } else {
            startObserving();
        }
    }
})();
</script>

<main>
