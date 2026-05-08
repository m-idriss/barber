(function () {
    function markBookingWidget() {
        var salon = document.getElementById('sln-salon') || document.querySelector('.sln-bootstrap');
        if (!salon) return false;

        salon.classList.add('ba-live-booking-shell');

        var widget = salon.closest('.elementor-widget') || salon.closest('.elementor-element') || salon.parentElement;
        if (widget) {
            widget.classList.add('ba-live-booking-widget');
        }

        return true;
    }

    function init() {
        if (markBookingWidget()) return;
        var attempts = 0;
        var timer = setInterval(function () {
            attempts++;
            if (markBookingWidget() || attempts > 40) {
                clearInterval(timer);
            }
        }, 250);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', markBookingWidget);
})();
