(function () {
    var cookiePath = (window.baAttendant && window.baAttendant.cookiePath) || '/';

    function getPref() {
        var m = document.cookie.match(/(?:^|; )sln_pref_att=(\d+)/);
        return m ? m[1] : null;
    }

    function clearPref() {
        document.cookie = 'sln_pref_att=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=' + cookiePath;
    }

    function tryAutoSelect() {
        var attId = getPref();
        if (!attId) return false;
        var form = document.getElementById('salon-step-attendant');
        if (!form) return false;
        var radio = form.querySelector('input[name="sln[attendant]"][value="' + attId + '"]');
        if (!radio) return false;

        var lbl = document.querySelector('label[for="' + radio.id + '"]');
        if (lbl) lbl.click(); else radio.click();

        clearPref();

        setTimeout(function () {
            var btn = document.getElementById('sln-step-submit');
            if (btn) btn.click();
        }, 400);

        return true;
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!tryAutoSelect()) {
            var observer = new MutationObserver(function () {
                if (tryAutoSelect()) observer.disconnect();
            });
            observer.observe(document.body, { childList: true, subtree: true });
            setTimeout(function () { observer.disconnect(); }, 30000);
        }
    });
})();
