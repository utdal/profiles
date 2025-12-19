/** Load JavaScript dependencies */
import Popper from 'popper.js';
window.Popper = Popper;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */
import $ from 'jquery';
window.$ = window.jQuery = $;

import 'bootstrap';
import 'bootstrap4-tagsinput';

/**
 * Font Awesome 5
 */
import { library, dom, config } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';

config.autoReplaceSvg = 'nest';
library.add(fas, far, fab);
// Kicks off the process of finding <i> tags and replacing with <svg>
dom.watch();
window.FontAwesomeDom = dom;

// Sortable
import Sortable from 'sortablejs/Sortable';
window.Sortable = Sortable;

// Typeahead Bloodhound
import 'corejs-typeahead/dist/typeahead.jquery.js';
import Bloodhound from 'corejs-typeahead/dist/bloodhound.js';
window.Bloodhound = Bloodhound;

// Trix editor
import 'trix';