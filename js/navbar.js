document.addEventListener("DOMContentLoaded", function () {
    const toggleSidebarButton = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const sidebarIcon = document.getElementById("sidebarIcon");
    const toggleSidebarButtonIcon = document.getElementById("toggleButtonIcon");
    const toggleSidebarButton2 = document.getElementById("toggleSidebarButton");

    toggleSidebarButton.addEventListener("click", () => {
        sidebar.classList.toggle("-translate-x-full");
        sidebarIcon.classList.toggle("rotate-180");

        // Controleer of de sidebar is ingeklapt en pas het pijl-icoon dienovereenkomstig aan
        const isSidebarCollapsed = sidebar.classList.contains("-translate-x-full");
        if (isSidebarCollapsed) {
            toggleSidebarButton2.classList.remove("hidden");
            toggleSidebarButtonIcon.classList.remove("rotate-180");
        } else {
            toggleSidebarButton2.classList.add("hidden");
            toggleSidebarButtonIcon.classList.add("rotate-180");
        }
    });

    toggleSidebarButton2.addEventListener("click", () => {
        sidebar.classList.toggle("-translate-x-full");
        sidebarIcon.classList.toggle("rotate-180");

        // Verberg de knop wanneer de sidebar wordt uitgeklapt
        toggleSidebarButton2.classList.add("hidden");
    });
});
