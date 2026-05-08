// Wait for a CSS selector to appear in the DOM, then call callback.
// Uses MutationObserver instead of polling; disconnects after timeout.
function waitForElement(selector, callback, timeout) {
    timeout = timeout !== undefined ? timeout : 4000;
    var el = document.querySelector(selector);
    if (el) { callback(el); return; }
    var done = false;
    var observer = new MutationObserver(function() {
        var found = document.querySelector(selector);
        if (found) { done = true; observer.disconnect(); callback(found); }
    });
    observer.observe(document.body, { childList: true, subtree: true });
    setTimeout(function() { if (!done) observer.disconnect(); }, timeout);
}

// Mobile nav toggle
(function() {
    var burger = document.getElementById('navBurger');
    var nav    = document.getElementById('mobileNav');
    if (!burger || !nav) return;

    var closeButtons = nav.querySelectorAll('[data-nav-close]');
    var menuLinks    = nav.querySelectorAll('a');

    function openNav() {
        nav.classList.add('is-open');
        burger.classList.add('is-active');
        burger.setAttribute('aria-expanded', 'true');
        nav.setAttribute('aria-hidden', 'false');
        document.body.classList.add('nav-open');
    }

    function closeNav() {
        nav.classList.remove('is-open');
        burger.classList.remove('is-active');
        burger.setAttribute('aria-expanded', 'false');
        nav.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('nav-open');
    }

    burger.addEventListener('click', function() {
        nav.classList.contains('is-open') ? closeNav() : openNav();
    });

    closeButtons.forEach(function(btn) { btn.addEventListener('click', closeNav); });
    menuLinks.forEach(function(link) { link.addEventListener('click', closeNav); });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) closeNav();
    });
})();

// Header scroll effect
(function() {
    var header = document.getElementById('siteHeader');
    if (!header) return;

    var ticking = false;

    function updateHeader() {
        header.classList.toggle('is-scrolled', window.scrollY > 30);
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }, { passive: true });
})();

// Move Conseil/Précision/Style section after the barbers (Elementor layout only)
(function() {
    function moveSignatureSection() {
        var targetTexts = ['conseil', 'précision', 'precision', 'style'];
        var allHeadings = document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title');
        var firstHeading = null;

        for (var i = 0; i < allHeadings.length; i++) {
            var text = (allHeadings[i].textContent || '').trim().toLowerCase();
            if (targetTexts.indexOf(text) !== -1) { firstHeading = allHeadings[i]; break; }
        }
        if (!firstHeading) return;

        var signatureSection = firstHeading.closest(
            '.elementor-top-section, .elementor-section, section.elementor-element[data-element_type="section"]'
        );
        if (!signatureSection || signatureSection.dataset.movedToHero === 'true') return;

        var barbersContainer =
            document.querySelector('.ba-el-hero-attendants') ||
            document.querySelector('.hero-team') ||
            document.querySelector('[class*="hero-attendant"]') ||
            document.querySelector('.sln-datashortcode--assistants') ||
            document.querySelector('.sln-datalist');

        if (!barbersContainer) {
            var sample = document.querySelector('.sln-datalist__item, .sln-datalist__item__image');
            if (sample) barbersContainer = sample.closest('.sln-datalist, .sln-datashortcode--assistants') || sample.parentElement;
        }
        if (!barbersContainer) return;

        var barbersAnchor =
            barbersContainer.closest('.elementor-widget') ||
            barbersContainer.closest('.elementor-element') ||
            barbersContainer;

        if (!barbersAnchor.parentNode || !signatureSection.parentNode) return;

        barbersAnchor.parentNode.insertBefore(signatureSection, barbersAnchor.nextSibling);
        signatureSection.dataset.movedToHero = 'true';
    }

    // Only run on Elementor pages
    if (!document.querySelector('.elementor')) return;

    waitForElement(
        '.elementor-heading-title, .ba-el-hero-attendants',
        function() { moveSignatureSection(); }
    );

    window.addEventListener('load', moveSignatureSection);
})();

// Remove SLN plugin inline width/margin styles that offset the booking widget
(function() {
    function fixSalonInlineStyles(salon) {
        salon.style.removeProperty('width');
        salon.style.removeProperty('margin-left');
        salon.style.removeProperty('margin-right');
        if (!salon.getAttribute('style') || salon.getAttribute('style').trim() === '') {
            salon.removeAttribute('style');
        }
    }

    waitForElement('#sln-salon', function(salon) {
        fixSalonInlineStyles(salon);

        // Keep watching for the plugin re-injecting inline styles
        if (!window.MutationObserver) return;
        var observer = new MutationObserver(function(mutations) {
            for (var i = 0; i < mutations.length; i++) {
                if (mutations[i].type === 'attributes' && mutations[i].attributeName === 'style') {
                    fixSalonInlineStyles(salon);
                }
            }
        });
        observer.observe(salon, { attributes: true, attributeFilter: ['style'] });
    });
})();

// Services / assistants collapse/expand (mobile only)
(function() {
    var toggles = document.querySelectorAll('.services-toggle[data-toggles]');
    for (var i = 0; i < toggles.length; i++) {
        (function(btn) {
            var grid = document.querySelector(btn.getAttribute('data-toggles'));
            if (!grid) return;
            var items = grid.querySelectorAll('.service-card, .team-card, .sln-datalist__item');
            if (items.length <= 4) { btn.hidden = true; return; }
            var label = btn.querySelector('span');
            btn.addEventListener('click', function() {
                var expanded = grid.classList.toggle('is-expanded');
                btn.setAttribute('aria-expanded', String(expanded));
                if (label) label.textContent = expanded ? 'Voir moins' : 'Voir plus';
            });
        })(toggles[i]);
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
        setTimeout(function() { overlay.classList.add('is-open'); }, 10);
        overlay.setAttribute('aria-hidden', 'false');
        trigger.setAttribute('aria-expanded', 'true');
        document.body.classList.add('nav-open');
        setTimeout(function() { if (input) input.focus(); }, 50);
    }

    function closeSearch() {
        overlay.classList.remove('is-open');
        overlay.setAttribute('hidden', '');
        overlay.setAttribute('aria-hidden', 'true');
        trigger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('nav-open');
        trigger.focus();
    }

    trigger.addEventListener('click', openSearch);
    if (closeBtn)  closeBtn.addEventListener('click', closeSearch);
    if (backdrop)  backdrop.addEventListener('click', closeSearch);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeSearch();
    });
})();
