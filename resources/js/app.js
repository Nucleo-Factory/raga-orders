import "./bootstrap";
import Sortable from "sortablejs";

window.Sortable = Sortable;

// Variables globales para el estado de la barra lateral
window.sidebarState = {
    initialized: false,
    expanded: false
};

// Función para inicializar la barra lateral
window.initializeSidebar = function(retryCount = 0) {
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

    // Cambiar el event listener a una delegación de eventos en el documento
    document.removeEventListener('click', handleTogglerClick); // Remover listener anterior si existe
    document.addEventListener('click', handleTogglerClick);

    // Mover la función del manejador fuera para poder removerla si es necesario
    function handleTogglerClick(e) {
        const togglerBtn = e.target.closest('.sidebar-toggler-btn');
        if (!togglerBtn) return;

        window.sidebarState.expanded = !window.sidebarState.expanded;

        if (window.sidebarState.expanded) {
            expandSidebar();
        } else {
            collapseSidebar();
        }
    }

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

    document.addEventListener('livewire:load', function() {
        // Inicializar cuando Livewire carga
        if (!window.sidebarState.expanded) {
            collapseSidebar();
        }
    });

    document.addEventListener('livewire:navigated', function() {
        // Reinicializar después de la navegación
        if (!window.sidebarState.expanded) {
            collapseSidebar();
        }
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

// Crear una función para manejar el click que esté disponible globalmente
window.handleSidebarToggle = function() {
    const sidebarEl = document.querySelector(".main-sidebar");

    if (!window.sidebarState) {
        window.sidebarState = {
            initialized: false,
            expanded: false
        };
    }

    window.sidebarState.expanded = !window.sidebarState.expanded;

    if (window.sidebarState.expanded) {
        expandSidebar();
    } else {
        collapseSidebar();
    }
};

// También podemos agregar un listener para turbo:load si estás usando Turbo
document.addEventListener('turbo:load', function() {
    if (!window.sidebarState.expanded) {
        collapseSidebar();
    }
});

// Definir las funciones globalmente
window.sidebarState = {
    initialized: false,
    expanded: false
};

// Función para expandir el sidebar
window.expandSidebar = function() {
    const sidebarEl = document.querySelector(".main-sidebar");
    const togglerBtn = document.querySelector(".sidebar-toggler-btn");
    const sidebarLinkAll = document.querySelectorAll(".sidebar-link");
    const sidebarLinkTextAll = document.querySelectorAll(".link-text");
    const logoutBtnText = document.querySelector(".logout-btn");
    const profileContainer = document.querySelector(".profile-container");
    const profileName = document.querySelector(".profile-name");
    const sidebarDropdowns = document.querySelectorAll('.sidebar-dropdown');
    const sidebarDropdownTextContainers = document.querySelectorAll(".sidebar-dropdown-text-container");
    const sidebarDropdownTexts = document.querySelectorAll(".sidebar-dropdown-text");
    const sidebarDropdownArrowIcons = document.querySelectorAll('.sidebar-dropdown .icon');
    const sidebarDropdownItemsText = document.querySelectorAll('.dropdown-item-text');

    if (!sidebarEl) return;

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

    if (togglerBtn) {
        togglerBtn.style.transform = "rotate(0deg)";
    }

    if (profileContainer) {
        profileContainer.style.gap = "0.625rem";
    }

    if (profileName) {
        profileName.style.width = profileName.scrollWidth + "px";
        profileName.style.opacity = "1";
    }

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
};

// Función para colapsar el sidebar
window.collapseSidebar = function() {
    const sidebarEl = document.querySelector(".main-sidebar");
    const togglerBtn = document.querySelector(".sidebar-toggler-btn");
    const sidebarLinkAll = document.querySelectorAll(".sidebar-link");
    const sidebarLinkTextAll = document.querySelectorAll(".link-text");
    const logoutBtnText = document.querySelector(".logout-btn");
    const profileContainer = document.querySelector(".profile-container");
    const profileName = document.querySelector(".profile-name");
    const sidebarDropdowns = document.querySelectorAll('.sidebar-dropdown');
    const sidebarDropdownTextContainers = document.querySelectorAll(".sidebar-dropdown-text-container");
    const sidebarDropdownTexts = document.querySelectorAll(".sidebar-dropdown-text");
    const sidebarDropdownArrowIcons = document.querySelectorAll('.sidebar-dropdown .icon');
    const sidebarDropdownItemsText = document.querySelectorAll('.dropdown-item-text');

    if (!sidebarEl) return;

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

    if (togglerBtn) {
        togglerBtn.style.transform = "rotate(180deg)";
    }

    if (profileContainer) {
        profileContainer.style.gap = "0";
    }

    if (profileName) {
        profileName.style.width = "0";
        profileName.style.opacity = "0";
    }

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
};

// Función para manejar el toggle del sidebar
window.handleSidebarToggle = function() {
    if (window.sidebarState.expanded) {
        window.collapseSidebar();
    } else {
        window.expandSidebar();
    }
};

// Inicialización cuando el DOM está listo
document.addEventListener("DOMContentLoaded", function() {
    window.collapseSidebar();
});

// Listeners para Livewire
if (typeof window.Livewire !== 'undefined') {
    document.addEventListener('livewire:load', function() {
        if (!window.sidebarState.expanded) {
            window.collapseSidebar();
        }
    });

    document.addEventListener('livewire:navigated', function() {
        if (!window.sidebarState.expanded) {
            window.collapseSidebar();
        }
    });
}

// Listener para Turbo si está siendo usado
document.addEventListener('turbo:load', function() {
    if (!window.sidebarState.expanded) {
        window.collapseSidebar();
    }
});
