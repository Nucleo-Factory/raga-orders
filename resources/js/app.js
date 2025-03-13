import "./bootstrap";
import Sortable from "sortablejs";

window.Sortable = Sortable;

const sidebarEl = document.querySelector(".main-sidebar");
const sidebarLinkAll = sidebarEl.querySelectorAll(".sidebar-link");
const sidebarLinkTextAll = sidebarEl.querySelectorAll(".link-text");

sidebarLinkAll.forEach((link) => {
    link.style.gap = "0";
});

sidebarLinkTextAll.forEach((linkText) => {
    linkText.style.width = "0";
    linkText.style.opacity = "0";
});

sidebarEl.addEventListener("mouseenter", function () {
    sidebarLinkAll.forEach((link) => {
        link.style.gap = "0.75rem";
    });

    sidebarLinkTextAll.forEach((linkText) => {
        linkText.style.width = linkText.scrollWidth + "px";
        linkText.style.opacity = "1";
    });
});

sidebarEl.addEventListener("mouseleave", function () {
    sidebarLinkAll.forEach((link) => {
        link.style.gap = "0";
    });

    sidebarLinkTextAll.forEach((linkText) => {
        linkText.style.width = "0";
        linkText.style.opacity = "0";
    });
});
