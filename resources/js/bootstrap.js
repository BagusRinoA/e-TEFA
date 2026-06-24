import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Mobile Menu Toggle & Admin Dropdown
document.addEventListener("DOMContentLoaded", function () {
    // Regular Mobile Menu for non-admin users
    const mobileMenuToggle = document.getElementById("mobile-menu-toggle");
    const mobileSidebar = document.getElementById("mobile-sidebar");
    const mobileOverlay = document.getElementById("mobile-overlay");
    const mobileMenuClose = document.getElementById("mobile-menu-close");

    if (mobileMenuToggle && mobileSidebar) {
        mobileMenuToggle.addEventListener("click", function () {
            mobileSidebar.classList.toggle("-translate-x-full");
            mobileOverlay?.classList.toggle("hidden");
        });
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener("click", function () {
            mobileSidebar?.classList.add("-translate-x-full");
            mobileOverlay.classList.add("hidden");
        });
    }

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener("click", function () {
            mobileSidebar?.classList.add("-translate-x-full");
            mobileOverlay?.classList.add("hidden");
        });
    }

    // Admin Dropdown Menu
    const adminMenuToggle = document.getElementById("admin-menu-toggle");
    const adminMenu = document.getElementById("admin-menu");

    if (adminMenuToggle && adminMenu) {
        adminMenuToggle.addEventListener("click", function (e) {
            e.stopPropagation();
            adminMenu.classList.toggle("hidden");
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            if (
                !adminMenuToggle.contains(e.target) &&
                !adminMenu.contains(e.target)
            ) {
                adminMenu.classList.add("hidden");
            }
        });

        // Close dropdown when clicking on a link
        const adminDropdownLinks = adminMenu.querySelectorAll(
            ".admin-dropdown-link",
        );
        adminDropdownLinks.forEach((link) => {
            link.addEventListener("click", function () {
                adminMenu.classList.add("hidden");
            });
        });
    }

    // Close mobile menu when clicking on a link
    const mobileNavLinks = document.querySelectorAll(".mobile-nav-link");
    mobileNavLinks.forEach((link) => {
        link.addEventListener("click", function () {
            mobileSidebar?.classList.add("-translate-x-full");
            mobileOverlay?.classList.add("hidden");
        });
    });
});
