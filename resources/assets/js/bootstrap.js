/** Load JavaScript dependencies */

window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * Font Awesome 5
 */
import fontawesome from '@fortawesome/fontawesome';
import solid from '@fortawesome/fontawesome-free-solid';
import regular from '@fortawesome/fontawesome-free-regular';
// import brands from '@fortawesome/fontawesome-free-brands';

fontawesome.library.add(solid);
fontawesome.library.add(regular);
// fontawesome.library.add(brands);

// Sortable
window.Sortable = require('sortablejs');

// Typeahead Bloodhound
window.Bloodhound = require('corejs-typeahead');