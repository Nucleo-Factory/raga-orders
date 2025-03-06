import './bootstrap';
import Sortable from 'sortablejs';

window.Sortable = Sortable;

const sidebarEl = document.querySelector('.main-sidebar');
const sidebarLinkText = sidebarEl.querySelectorAll('.link-text');

sidebarLinkText.forEach(link => {
    link.style.width = '0'
});

sidebarEl.addEventListener('mouseenter', function() {
    sidebarLinkText.forEach(link => {
        link.style.width = link.scrollWidth + 'px';
    });
});

sidebarEl.addEventListener('mouseleave', function() {
    sidebarLinkText.forEach(link => {
        link.style.width = '0'
    });
});