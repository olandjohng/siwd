//Apex Chart
document.addEventListener('DOMContentLoaded', function() {
    const sidebarMenu = document.getElementById('sidebarMenu');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    sidebarToggle.addEventListener('click', function() {
        sidebarMenu.classList.toggle('contracted');
    });
});

