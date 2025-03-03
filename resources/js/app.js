import './bootstrap';
import Sortable from 'sortablejs';

window.Sortable = Sortable;

const sidebarEl = document.querySelector('.main-sidebar')

sidebarEl.addEventListener('mouseenter', function() {
    sidebarEl.classList.add('active')
})

sidebarEl.addEventListener('mouseleave', function() {
    sidebarEl.classList.remove('active')
})
