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
window.Sortable = require('sortablejs/Sortable');

// Typeahead Bloodhound
window.Bloodhound = require('corejs-typeahead');

// Trix editor
require('trix');

// Register text attributes
Trix.config.textAttributes.sub = {
  tagName: "sub",
  inheritable: true,
};

Trix.config.textAttributes.sup = {
  tagName: "sup",
  inheritable: true,
};

addEventListener("trix-initialize", function (event) {
  const toolbar = event.target.toolbarElement;
  const buttonGroup = toolbar.querySelector(".trix-button-group--block-tools");

  if (!buttonGroup) return;

  const hasButton = (attribute) =>
    buttonGroup.querySelector(
      `.trix-button[data-trix-attribute="${attribute}"]`
    );

  const buttonsToAdd = [];

  if (!hasButton("sub")) {
    buttonsToAdd.push(`
      <button
        type="button"
        class="trix-button"
        data-trix-attribute="sub"
        title="Subscript"
        tabindex="-1">
        <sub>SUB</sub>
      </button>
    `);
  }

  if (!hasButton("sup")) {
    buttonsToAdd.push(`
      <button
        type="button"
        class="trix-button"
        data-trix-attribute="sup"
        title="Superscript"
        tabindex="-1">
        <sup>SUP</sup>
      </button>
    `);
  }

  if (buttonsToAdd.length) {
    buttonGroup.insertAdjacentHTML("beforeend", buttonsToAdd.join(""));
  }
});
