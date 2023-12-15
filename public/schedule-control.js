window.addEventListener('DOMContentLoaded', function () {
    // Direct link to tab
    const url = window.location.href;
    if (url.includes('#')) {
        const hashValue = url.split('#')[1];
        const tabLink = document.querySelector('.nav-pills a[href="#' + hashValue + '"]');
        if (tabLink) {
            tabLink.click();
        }
    }

    // Change hash for page-reload
    const tabs = document.querySelectorAll('.nav-pills a');
    tabs.forEach(function (tab) {
        tab.addEventListener('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
    });
});
