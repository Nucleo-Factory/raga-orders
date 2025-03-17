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
    const sidebarDropdown = sidebarEl.querySelector('.sidebar-dropdown')
    const sidebarDropdownTextContainer = sidebarEl.querySelector(
        ".sidebar-dropdown-text-container"
    );
    const sidebarDropdownText = sidebarEl.querySelector(
        ".sidebar-dropdown-text"
    );
    const sidebarDropdownArrowIcon = sidebarEl.querySelector('.sidebar-dropdown .icon');

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

        sidebarDropdown.style.gap = "0.75rem"

        sidebarDropdownTextContainer.style.gap = "0.625rem";

        sidebarDropdownText.style.width =
            sidebarDropdownText.scrollWidth + "px";
        sidebarDropdownText.style.opacity = "1";

        sidebarDropdownArrowIcon.style.width = sidebarDropdownArrowIcon.scrollWidth + "px";
        sidebarDropdownArrowIcon.style.opacity = "1"
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

        sidebarDropdown.style.gap = "0"

        sidebarDropdownTextContainer.style.gap = "0";

        sidebarDropdownText.style.width = "0";
        sidebarDropdownText.style.opacity = "0";

        sidebarDropdownArrowIcon.style.width = "0";
        sidebarDropdownArrowIcon.style.opacity = "0"
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
