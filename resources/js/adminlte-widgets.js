// Lightweight replacements for AdminLTE widget behaviors used in this project.
(function () {
    function closestNavItem(element) {
        return element.closest('.nav-item');
    }

    // Sidebar push menu
    document.addEventListener('click', function (event) {
        const btn = event.target.closest('[data-widget="pushmenu"]');
        if (!btn) return;
        event.preventDefault();
        document.body.classList.toggle('sidebar-collapse');
    });

    // Fullscreen toggle
    document.addEventListener('click', async function (event) {
        const btn = event.target.closest('[data-widget="fullscreen"]');
        if (!btn) return;
        event.preventDefault();

        if (!document.fullscreenElement) {
            await document.documentElement.requestFullscreen();
        } else {
            await document.exitFullscreen();
        }
    });

    // Sidebar treeview
    document.addEventListener('click', function (event) {
        const link = event.target.closest('.nav-sidebar .nav-item > .nav-link');
        if (!link) return;

        const item = closestNavItem(link);
        if (!item) return;

        const subMenu = item.querySelector(':scope > .nav-treeview');
        if (!subMenu) return;

        event.preventDefault();
        item.classList.toggle('menu-open');
    });
})();
