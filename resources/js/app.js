import "./bootstrap";
import Sortable from "sortablejs";

window.Sortable = Sortable;

document.addEventListener("DOMContentLoaded", function () {
    const sidebarEl = document.querySelector(".main-sidebar");
    const sidebarLinkAll = sidebarEl.querySelectorAll(".sidebar-link");
    const sidebarLinkTextAll = sidebarEl.querySelectorAll(".link-text");
    const logoutBtnText = sidebarEl.querySelector(".logout-btn");
    const togglerBtn = sidebarEl.querySelector(".sidebar-toggler-btn");
    const profileContainer = sidebarEl.querySelector(".profile-container");
    const profileName = sidebarEl.querySelector(".profile-name");
    const sidebarDropdowns = sidebarEl.querySelectorAll('.sidebar-dropdown');
    const sidebarDropdownTextContainers = sidebarEl.querySelectorAll(".sidebar-dropdown-text-container");
    const sidebarDropdownTexts = sidebarEl.querySelectorAll(".sidebar-dropdown-text");
    const sidebarDropdownArrowIcons = sidebarEl.querySelectorAll('.sidebar-dropdown .icon');
    const sidebarDropdownItemsText = sidebarEl.querySelectorAll('.dropdown-item-text');

    let sidebarExpanded = false;

    function expandSidebar() {
        sidebarEl.classList.add("sidebar-expanded");
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

        profileContainer.style.gap = "0.625rem";
        profileName.style.width = profileName.scrollWidth + "px";
        profileName.style.opacity = "1";

        sidebarDropdowns.forEach((dropdown) => {
            dropdown.style.gap = "0.75rem";
        });

        sidebarDropdownTextContainers.forEach((container) => {
            container.style.gap = "0.625rem";
        });

        sidebarDropdownTexts.forEach((text) => {
            text.style.width = text.scrollWidth + "px";
            text.style.opacity = "1";
        });

        sidebarDropdownArrowIcons.forEach((icon) => {
            icon.style.width = icon.scrollWidth + "px";
            icon.style.opacity = "1";
        });

        sidebarDropdownItemsText.forEach((itemText) => {
            itemText.style.width = itemText.scrollWidth + "px";
            itemText.style.opacity = "1";
        });
    }

    function collapseSidebar() {
        sidebarEl.classList.remove("sidebar-expanded");

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

        profileContainer.style.gap = "0";
        profileName.style.width = "0";
        profileName.style.opacity = "0";

        sidebarDropdowns.forEach((dropdown) => {
            dropdown.style.gap = "0";
        });

        sidebarDropdownTextContainers.forEach((container) => {
            container.style.gap = "0";
        });

        sidebarDropdownTexts.forEach((text) => {
            text.style.width = "0";
            text.style.opacity = "0";
        });

        sidebarDropdownArrowIcons.forEach((icon) => {
            icon.style.width = "0";
            icon.style.opacity = "0";
        });

        sidebarDropdownItemsText.forEach((itemText) => {
            itemText.style.width = "0";
            itemText.style.opacity = "0";
        });
    }

    togglerBtn.addEventListener("click", function () {
        sidebarExpanded = !sidebarExpanded;

        if (sidebarExpanded) {
            expandSidebar();
        } else {
            collapseSidebar();
        }
    });
});
