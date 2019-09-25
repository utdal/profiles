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
    require('bootstrap4-tagsinput');
} catch (e) {}

/**
 * Font Awesome 5
 */
import { library, dom } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

library.add(fas, far);
// Kicks off the process of finding <i> tags and replacing with <svg>
dom.watch();

// Sortable
window.Sortable = require('sortablejs');

// Typeahead Bloodhound
window.Bloodhound = require('corejs-typeahead');

// Trix editor
require('trix');