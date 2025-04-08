import "./bootstrap";
import Sortable from "sortablejs";

window.Sortable = Sortable;

// Variables globales para el estado de la barra lateral
window.sidebarState = {
    initialized: false,
    expanded: false
};

// Función para inicializar la barra lateral
function initializeSidebar(retryCount = 0) {
    const maxRetries = 5;
    const sidebarEl = document.querySelector(".main-sidebar");
    const togglerBtn = document.querySelector(".sidebar-toggler-btn");

    // Si no encontramos los elementos y aún tenemos reintentos disponibles, programamos otro intento
    if ((!sidebarEl || !togglerBtn) && retryCount < maxRetries) {
        console.log(`Intentando inicializar la barra lateral (intento ${retryCount + 1})`);
        setTimeout(() => initializeSidebar(retryCount + 1), 300);
        return;
    }

    // Si ya se inicializó, no hacemos nada
    if (window.sidebarState.initialized) {
        return;
    }

    if (!sidebarEl || !togglerBtn) {
        console.error("No se pudo encontrar la barra lateral o el botón después de varios intentos");
        return;
    }

    // Marcamos como inicializado
    window.sidebarState.initialized = true;

    const sidebarLinkAll = sidebarEl.querySelectorAll(".sidebar-link");
    const sidebarLinkTextAll = sidebarEl.querySelectorAll(".link-text");
    const logoutBtnText = sidebarEl.querySelector(".logout-btn");
    const profileContainer = sidebarEl.querySelector(".profile-container");
    const profileName = sidebarEl.querySelector(".profile-name");
    const sidebarDropdowns = sidebarEl.querySelectorAll('.sidebar-dropdown');
    const sidebarDropdownTextContainers = sidebarEl.querySelectorAll(".sidebar-dropdown-text-container");
    const sidebarDropdownTexts = sidebarEl.querySelectorAll(".sidebar-dropdown-text");
    const sidebarDropdownArrowIcons = sidebarEl.querySelectorAll('.sidebar-dropdown .icon');
    const sidebarDropdownItemsText = sidebarEl.querySelectorAll('.dropdown-item-text');

    function expandSidebar() {
        window.sidebarState.expanded = true;
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
        window.sidebarState.expanded = false;
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

    // Agregar el event listener con captura
    togglerBtn.addEventListener("click", function(e) {
        window.sidebarState.expanded = !window.sidebarState.expanded;

        if (window.sidebarState.expanded) {
            expandSidebar();
        } else {
            collapseSidebar();
        }
    }, true);

    // Inicializar directamente una vez
    collapseSidebar();
}

// Múltiples puntos de inicialización para asegurar que se ejecute
// 1. Lo más temprano posible
initializeSidebar();

// 2. Cuando el DOM está listo
document.addEventListener("DOMContentLoaded", function() {
    initializeSidebar();
});

// 3. Cuando la página se carga completamente
window.addEventListener('load', function() {
    initializeSidebar();
});

// 4. Cuando Livewire se inicializa (si está usando Livewire)
if (typeof window.Livewire !== 'undefined') {
    document.addEventListener('livewire:load', function() {
        initializeSidebar();

        Livewire.hook('message.processed', () => {
            initializeSidebar();
        });
    });
}

// 5. Ejecutar periódicamente hasta que se inicialice (como último recurso)
const sidebarInitInterval = setInterval(() => {
    if (window.sidebarState.initialized) {
        clearInterval(sidebarInitInterval);
    } else {
        initializeSidebar();
    }
}, 500);

// Limpiar el intervalo después de 10 segundos en cualquier caso
setTimeout(() => {
    clearInterval(sidebarInitInterval);
}, 10000);

// Agregar esta función global
window.toggleSidebar = function(expanded) {
    const sidebarEl = document.querySelector(".main-sidebar");
    // Aquí el resto de la lógica para expandir/colapsar
};
