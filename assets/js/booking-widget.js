(function() {
    function markBookingWidget(salon) {
        salon.classList.add('ba-live-booking-shell');
        var widget = salon.closest('.elementor-widget') || salon.closest('.elementor-element') || salon.parentElement;
        if (widget) widget.classList.add('ba-live-booking-widget');

        var hero = salon.closest('.hero-booking');
        var title = hero ? hero.querySelector('.sln-salon-title') : null;
        if (title && title.textContent.trim().toLowerCase() === 'book') {
            title.textContent = 'Book an appointment';
        }
    }

    if (window.baWaitForElement) {
        window.baWaitForElement('#sln-salon', markBookingWidget);
    }

    window.addEventListener('load', function() {
        var salon = document.getElementById('sln-salon') || document.querySelector('.sln-bootstrap');
        if (salon) markBookingWidget(salon);
    });
})();
