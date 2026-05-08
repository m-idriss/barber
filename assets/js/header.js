// Mobile nav toggle
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

    let ticking = false;

    function updateHeader() {
        if (window.scrollY > 30) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
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

        const salonInner = document.querySelector('#sln-salon, .sln-bootstrap');
        log('salonInner found:', !!salonInner, salonInner);
        if (!salonInner) {
            log('No #sln-salon found, retrying in 1s...');
            return false;
        }

        const widgetWrapper = salonInner.closest('.elementor-widget') || salonInner.closest('.hero-booking') || salonInner.parentElement;
        log('widgetWrapper:', widgetWrapper);
        if (!widgetWrapper) return false;

        const heroTitle = document.querySelector(
            '.ba-el-hero h1, .ba-el-hero .elementor-heading-title, .hero h1, .elementor-section h1, h1.elementor-heading-title'
        );
        log('heroTitle:', heroTitle);
        if (!heroTitle) return false;

        const titleWrapper = heroTitle.closest('.elementor-widget') || heroTitle.closest('.elementor-element') || heroTitle;
        log('titleWrapper:', titleWrapper);

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

        const firstHeading = foundHeadings[0].element;
        const signatureSection = firstHeading.closest('.elementor-top-section, .elementor-section, section.elementor-element[data-element_type="section"]');
        log('signatureSection:', signatureSection);

        if (!signatureSection) {
            log('No parent section found');
            return false;
        }

        let barbersContainer =
            document.querySelector('.ba-el-hero-attendants') ||
            document.querySelector('.hero-team') ||
            document.querySelector('[class*="hero-attendant"]') ||
            document.querySelector('.sln-datashortcode--assistants') ||
            document.querySelector('.sln-datalist');

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

        const barbersAnchor =
            barbersContainer.closest('.elementor-widget') ||
            barbersContainer.closest('.elementor-element') ||
            barbersContainer;
        log('barbersAnchor:', barbersAnchor);

        if (signatureSection.dataset.movedToHero === 'true') {
            log('Already moved, skipping');
            return true;
        }

        if (!barbersAnchor.parentNode || !signatureSection.parentNode) {
            log('Missing parent node');
            return false;
        }

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

// Annule les styles inline du plugin SLN qui décale le widget de réservation
(function() {
    function fixSalonInlineStyles() {
        const salon = document.getElementById('sln-salon');
        if (!salon) return false;

        salon.style.removeProperty('width');
        salon.style.removeProperty('margin-left');
        salon.style.removeProperty('margin-right');
        if (!salon.getAttribute('style') || salon.getAttribute('style').trim() === '') {
            salon.removeAttribute('style');
        }
        return true;
    }

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

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(fixSalonInlineStyles, 100);
    });

    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            for (const m of mutations) {
                if (m.type === 'attributes' && m.attributeName === 'style' && m.target.id === 'sln-salon') {
                    fixSalonInlineStyles();
                }
            }
        });

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

// Search overlay
(function() {
    var trigger  = document.getElementById('searchTrigger');
    var overlay  = document.getElementById('searchOverlay');
    var backdrop = document.getElementById('searchBackdrop');
    var closeBtn = document.getElementById('searchClose');
    var input    = document.getElementById('searchInput');
    if (!trigger || !overlay) return;

    function openSearch() {
        overlay.removeAttribute('hidden');
        setTimeout(function() {
            overlay.classList.add('is-open');
        }, 10);
        overlay.setAttribute('aria-hidden', 'false');
        trigger.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        setTimeout(function() { if (input) input.focus(); }, 50);
    }

    function closeSearch() {
        overlay.classList.remove('is-open');
        overlay.setAttribute('hidden', '');
        overlay.setAttribute('aria-hidden', 'true');
        trigger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        trigger.focus();
    }

    trigger.addEventListener('click', openSearch);
    if (closeBtn)  closeBtn.addEventListener('click', closeSearch);
    if (backdrop)  backdrop.addEventListener('click', closeSearch);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
            closeSearch();
        }
    });
})();
