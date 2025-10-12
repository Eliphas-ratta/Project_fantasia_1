import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';
import Swal from 'sweetalert2';
window.Swal = Swal;



document.addEventListener('DOMContentLoaded', () => {
  const dropdown = document.querySelector('.fantasia-dropdown');
  const toggle = dropdown?.querySelector('.fantasia-dropdown-toggle');
  const menu = dropdown?.querySelector('.fantasia-dropdown-menu');

  if (toggle && menu) {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      menu.classList.toggle('show');
    });

    // Fermer quand on clique ailleurs
    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) {
        menu.classList.remove('show');
      }
    });
  }
});
