(function() {
    function waitFor(selector, callback, timeout) {
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

    function markBookingWidget(salon) {
        salon.classList.add('ba-live-booking-shell');
        var widget = salon.closest('.elementor-widget') || salon.closest('.elementor-element') || salon.parentElement;
        if (widget) widget.classList.add('ba-live-booking-widget');
    }

    waitFor('#sln-salon', markBookingWidget);

    window.addEventListener('load', function() {
        var salon = document.getElementById('sln-salon') || document.querySelector('.sln-bootstrap');
        if (salon) markBookingWidget(salon);
    });
})();
