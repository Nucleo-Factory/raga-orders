import "./bootstrap";
import Sortable from "sortablejs";

window.Sortable = Sortable;

document.addEventListener("DOMContentLoaded", function() {
    const sidebarEl = document.querySelector(".main-sidebar");
    const sidebarLinkAll = sidebarEl.querySelectorAll(".sidebar-link");
    const sidebarLinkTextAll = sidebarEl.querySelectorAll(".link-text");
    const logoutBtnText = sidebarEl.querySelector(".logout-btn");
    const togglerBtn = document.querySelector(".sidebar-toggler-btn");

    let sidebarExpanded = false;

    function expandSidebar() {
        sidebarLinkAll.forEach((link) => {
            link.style.gap = "0.75rem";
        });

        sidebarLinkTextAll.forEach((linkText) => {
            linkText.style.width = linkText.scrollWidth + "px";
            linkText.style.opacity = "1";
        });

        if (logoutBtnText) {
            logoutBtnText.style.gap = "0.625rem";
        }

        togglerBtn.style.transform = "rotate(0deg)";
    }

    function collapseSidebar() {
        sidebarLinkAll.forEach((link) => {
            link.style.gap = "0";
        });

        sidebarLinkTextAll.forEach((linkText) => {
            linkText.style.width = "0";
            linkText.style.opacity = "0";
        });

        if (logoutBtnText) {
            logoutBtnText.style.gap = "0";
        }

        togglerBtn.style.transform = "rotate(180deg)";
    }

    togglerBtn.addEventListener("click", function() {
        sidebarExpanded = !sidebarExpanded;

        if (sidebarExpanded) {
            expandSidebar();
        } else {
            collapseSidebar();
        }
    });
});