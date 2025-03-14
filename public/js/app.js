(self["webpackChunk"] = self["webpackChunk"] || []).push([["/js/app"],{

/***/ "./resources/assets/js/app.js":
/*!************************************!*\
  !*** ./resources/assets/js/app.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

/* provided dependency */ var jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/src/jquery.js");
/* provided dependency */ var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/src/jquery.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

__webpack_require__(/*! ./bootstrap */ "./resources/assets/js/bootstrap.js");
window.this_url = window.this_url || '';

/** Global Profiles Module */
var profiles = function ($, undefined) {
  /** @type {string} the current URL */
  var this_url = window.this_url;

  /** @type {Object} config settings */
  var config = {
    datepicker: {
      year: {
        autoclose: true,
        assumeNearbyYear: true,
        clearBtn: true,
        forceParse: false,
        keepEmptyValues: true,
        minViewMode: 2,
        format: 'yyyy'
      },
      month: {
        autoclose: true,
        assumeNearbyYear: true,
        clearBtn: true,
        forceParse: false,
        keepEmptyValues: true,
        minViewMode: 1,
        format: 'yyyy/mm'
      }
    }
  };

  /**
   * Checks to see if an input is empty.
   *
   * @param {HTMLElement} input
   * @return {boolean}
   */
  var _input_is_empty = function _input_is_empty(input) {
    if (!(input instanceof HTMLInputElement)) {
      return true;
    }
    switch (input.getAttribute('type')) {
      case 'file':
        return input.files.length == 0;
      case 'checkbox':
        return !input.checked;
      // @todo: other input types
      default:
        return input.value == null || input.value == '';
    }
  };

  /**
   * Sets img src to selected file object
   *
   * @param {Event} event the triggered event
   */
  var preview_selected_image = function preview_selected_image(event) {
    var file_input = event.target;
    var id = file_input.id.replace(/\[/g, '\\[').replace(/\]/g, '\\]');
    $("label[for=\"".concat(id, "\"]")).addClass('active').text(file_input.files[0].name);
    $("#".concat(id, "-img")).attr('src', window.URL.createObjectURL(file_input.files[0]));
    $(file_input).siblings('.invalid-feedback').removeClass('d-block');
  };

  /**
   * Reindex numbers in field ids, names, and labels to match sort order,
   * e.g. if the first item's name is "thing[3]", rename it to "thing[0]"
   * 
   * @param {NodeList} list_items - the ordered list of items to reindex
   */
  var reindex_sorted_list = function reindex_sorted_list(list_items) {
    for (var i = 0; i < list_items.length; i++) {
      if (list_items[i].dataset.rowId) {
        list_items[i].dataset.rowId = i;
      }
      var search_for = /\[\d+\]/g;
      var replace_with = "[".concat(i, "]");
      var _iterator = _createForOfIteratorHelper(list_items[i].querySelectorAll('[name]')),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var field = _step.value;
          field.name = field.name.replace(search_for, replace_with);
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      var _iterator2 = _createForOfIteratorHelper(list_items[i].querySelectorAll('[id]')),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var _field = _step2.value;
          _field.id = _field.id.replace(search_for, replace_with);
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
      var _iterator3 = _createForOfIteratorHelper(list_items[i].querySelectorAll('label[for]')),
        _step3;
      try {
        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          var _field2 = _step3.value;
          _field2.htmlFor = _field2.htmlFor.replace(search_for, replace_with);
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }
    }
  };

  /**
   * Actions to take after updating a list
   * 
   * @param {HTMLElement} el - the container for the sorted elements
   * @param {string} actions - the action(s) to take, comma-separated
   * @return {void}
   */
  var on_list_updated = function on_list_updated(el, actions) {
    if (typeof actions !== 'string') {
      return;
    }
    var _iterator4 = _createForOfIteratorHelper(actions.split(',').map(function (i) {
        return i.trim();
      })),
      _step4;
    try {
      for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
        var action = _step4.value;
        if (action === 'reindex') {
          reindex_sorted_list(el.children);
        }
        if (action === 'reset-next-row-id') {
          el.dataset.nextRowId = String(Number(el.dataset.nextRowId) > 0 ? el.children.length : -1);
        }
      }
    } catch (err) {
      _iterator4.e(err);
    } finally {
      _iterator4.f();
    }
  };

  /**
   * Display spinner animation and disable form submit button
   *
   * @param {HTMLElement} form that gets submitted 
   */
  var wait_when_submitting = function wait_when_submitting(form) {
    elem = form.querySelector('button[type=submit]');
    elem_text = elem.innerHTML.replace(/<i[^>]*>(.*?)<\/i>/g, '');
    elem.innerHTML = "<i class=\"fas fa-spinner fa-spin fa-fw\"></i> ".concat(elem_text);
    elem.classList.add('btn-primary', 'disabled');
    elem.classList.remove('btn-light', 'btn-dark', 'btn-secondary', 'btn-info', 'btn-success', 'btn-warning', 'btn-danger');
    elem.disabled = true;
  };

  /**
   * Adds a new item input row
   *
   * @param {Event} event the triggered event
   * @this {HTMLElement} the DOM element that was clicked
   */
  var add_row = function add_row(event) {
    var _options$template, _document$querySelect;
    var options = event.target.dataset;
    var item_template = document.querySelector((_options$template = options.template) !== null && _options$template !== void 0 ? _options$template : 'form .record');
    var item_container = (_document$querySelect = document.querySelector(options.insertInto)) !== null && _document$querySelect !== void 0 ? _document$querySelect : item_template.parentElement;
    if (item_template) {
      var _new_item$querySelect, _new_item$querySelect2, _new_item$querySelect3, _new_item$querySelect4, _new_item$querySelect5, _new_item$querySelect6, _new_item$querySelect7, _new_item$querySelect8, _new_item$querySelect9, _new_item$querySelect10;
      var old_id = item_template.dataset.rowId;
      var new_id;
      if (Number(item_container.dataset.nextRowId) >= 0) {
        new_id = String(item_container.dataset.nextRowId++);
      } else {
        new_id = String(item_container.dataset.nextRowId--);
      }
      var new_item = item_template.cloneNode(true);
      new_item.dataset.rowId = new_id;
      (_new_item$querySelect = new_item.querySelectorAll('input:not([type="button"]), textarea, select')) === null || _new_item$querySelect === void 0 || _new_item$querySelect.forEach(function (el) {
        el.id = el.id.replace(old_id, new_id);
        el.setAttribute('name', el.name.replace(old_id, new_id));
        el.setAttribute('value', '');
        el.value = '';
      });
      (_new_item$querySelect2 = new_item.querySelectorAll("input[type=\"hidden\"][name$=\"[id]\"]")) === null || _new_item$querySelect2 === void 0 || _new_item$querySelect2.forEach(function (el) {
        el.id = el.name;
        el.value = new_id;
      });
      (_new_item$querySelect3 = new_item.querySelectorAll('label')) === null || _new_item$querySelect3 === void 0 || _new_item$querySelect3.forEach(function (el) {
        var _el$getAttribute;
        el.setAttribute('for', (_el$getAttribute = el.getAttribute('for')) === null || _el$getAttribute === void 0 ? void 0 : _el$getAttribute.replace(old_id, new_id));
      });
      (_new_item$querySelect4 = new_item.querySelectorAll('trix-editor')) === null || _new_item$querySelect4 === void 0 || _new_item$querySelect4.forEach(function (el) {
        el.setAttribute('input', el.getAttribute('input').replace(old_id, new_id));
      });
      (_new_item$querySelect5 = new_item.querySelectorAll('img')) === null || _new_item$querySelect5 === void 0 || _new_item$querySelect5.forEach(function (el) {
        el.id = el.id.replace(old_id, new_id);
        el.src = '';
      });
      (_new_item$querySelect6 = new_item.querySelectorAll('.custom-file-label')) === null || _new_item$querySelect6 === void 0 || _new_item$querySelect6.forEach(function (el) {
        el.id = el.id.replace(old_id, new_id);
        el.innerHTML = 'Select an image';
      });
      (_new_item$querySelect7 = new_item.querySelectorAll('.actions .trash')) === null || _new_item$querySelect7 === void 0 || _new_item$querySelect7.forEach(function (el) {
        $(el).on('click', function () {
          return clear_row(el);
        });
      });
      (_new_item$querySelect8 = new_item.querySelectorAll('input[type="file"][accept^="image"]')) === null || _new_item$querySelect8 === void 0 || _new_item$querySelect8.forEach(function (el) {
        $(el).on('change', function (event) {
          return preview_selected_image(event);
        });
      });
      (_new_item$querySelect9 = new_item.querySelectorAll('.datepicker.year')) === null || _new_item$querySelect9 === void 0 || _new_item$querySelect9.forEach(function (el) {
        $(el).datepicker(config.datepicker.year);
      });
      (_new_item$querySelect10 = new_item.querySelectorAll('.datepicker.month')) === null || _new_item$querySelect10 === void 0 || _new_item$querySelect10.forEach(function (el) {
        $(el).datepicker(config.datepicker.month);
      });
      $(new_item).hide();
      if ('insertType' in options && options.insertType === 'prepend') {
        item_container.prepend(new_item);
      } else {
        item_container.append(new_item);
      }
      $(new_item).slideDown();
    }
  };

  /**
   * Clears an input text or textarea row
   *
   * @param {HTMLElement} elem
   */
  var clear_row = function clear_row(elem) {
    parent_elem = $(elem).parent().parent();
    parent_elem.slideUp().find("input[type=text], input[type=url], input[type=month], input.clearable, textarea, select").val('');
    var list_container = parent_elem[0].parentElement;
    if (elem.dataset.remove === 'true') {
      parent_elem.remove();
    }
    if (elem.dataset.onRemove) {
      on_list_updated(list_container, elem.dataset.onRemove);
    }
  };

  /**
   * Toggles a class on an element or specified target.
   *
   * The class to toggle may be specified in the [data-toggle-class=] attribute.
   * Optional target element may be specified in the [data-target=] attribute.
   *
   * @param {Event} evt - jQuery event object
   * @this {HTMLElement} - the DOM element that was clicked
   */
  var toggle_class = function toggle_class(evt) {
    var $this = $(this);
    var $target = $this.data('target') ? $($this.data('target')) : $this;
    $target.toggleClass($this.data('toggle-class'));
  };

  /**
   * Replaces an existing FontAwesome icon with another.
   *
   * The replacement icon may be specified in the [data-newicon=] attribute.
   * Optional target (existing icon parent) element may be specified in the [data-target=] attribute.
   * Optional input element to check for emptiness may be specified in the [data-inputrequired=] attribute.
   *
   * @param {Event} evt - jQuery event object
   * @this {HTMLElement} - the DOM element for which the event was registered
   */
  var replace_icon = function replace_icon(evt) {
    if (this.dataset.inputrequired && _input_is_empty(document.querySelector(this.dataset.inputrequired))) {
      return;
    }
    var target = this.dataset.target ? document.querySelector(this.dataset.target) : this;
    target.querySelector('[data-fa-i2svg]').className = this.dataset.newicon;

    // this shouldn't be needed, but for some reason Chrome occasionally fails
    // to propogate when a submit button is clicked.
    if (this.getAttribute('type') === 'submit') {
      $(this).closest('form').submit();
    }
  };

  /**
   * Display a dynamic toast alert
   * 
   * @param {String} message - the message to display
   * @param {String} type - alert type, e.g. primary, success, warning, danger, and etc.
   */
  var toast = function toast(message, type) {
    var flash_container = document.querySelector('.flash-container');
    if (!flash_container) {
      flash_container = document.createElement('div');
      flash_container.classList = 'flash-container';
      document.body.appendChild(flash_container);
    }
    var flash_message = document.createElement('div');
    flash_message.classList = 'flash-message alert-dismissable alert-' + (type || 'success');
    flash_message.setAttribute('role', 'alert');
    flash_message.setAttribute('aria-live', 'assertive');
    flash_message.setAttribute('aria-atomic', 'true');
    flash_message.innerHTML = message;
    flash_container.appendChild(flash_message);
    flash_message.addEventListener('click', function (e) {
      e.target.style.display = 'none';
    });
    $(flash_message).animate({
      opacity: 0
    }, {
      duration: 5000,
      complete: function complete() {
        return flash_message.style.display = 'none';
      }
    });
  };

  /**
   * Deobfuscate an email address
   * 
   * @param {String} obfuscated_mail_address - the obfuscated
   * @see App\Helpers\Utils for obfuscation strategy
   */
  var deobfuscate_mail = function deobfuscate_mail(obfuscated_mail_address) {
    return obfuscated_mail_address.replace(/[a-z]/gi, function (c) {
      return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    }).replace('☄️', '@').split('@').reverse().join('@');
  };

  /**
   * Deobfuscate email address HTML links
   *
   * @param {HTMLElement} i - the element index
   * @param {HTMLElement} el - the DOM element
   */
  var deobfuscate_mail_links = function deobfuscate_mail_links(i, el) {
    el.innerText = deobfuscate_mail(el.id);
    el.href = "mailto:" + el.innerText;
  };
  var toggle_show = function toggle_show(evt) {
    var $this = $(this);
    var target = $this.data('toggle-target') || this;
    var toggle_value = $this.data('toggle-value') || true;
    var current_value = $this.val();
    if ($this.is('input[type=radio], input[type=checkbox]')) {
      current_value = $this.prop('checked');
    }
    if (current_value == toggle_value) {
      $(target).slideDown(200).find(':input').prop('disabled', false);
    } else {
      $(target).slideUp(200).find(':input').prop('disabled', true);
    }
  };

  /**
   * Creates and initializes Bootstrap-Tagsinput / Typeahead.js Profile Picker.
   *
   * @param  {String} selector : CSS selector for the input field to register
   * @param  {String} api      : URL to the profile API
   * @return {void}
   */
  var registerProfilePicker = function registerProfilePicker(selector, api) {
    if (typeof api === 'undefined') api = this_url + '/api/v1?with_data=1&data_type=information&public=1';
    var $select = $(selector);
    if ($select.length === 0) return;
    if ($select.data('school')) {
      api += '&from_school=' + $select.data('school');
    }
    if ($select.data('accepting-undergrad')) {
      api += '&accepting_undergrad=' + $select.data('accepting-undergrad');
    }
    var profileSearch = new Bloodhound({
      datumTokenizer: function datumTokenizer(profiles) {
        return Bloodhound.tokenizers.whitespace(profiles.value);
      },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 50,
      remote: {
        url: api + '&search_names=%QUERY',
        wildcard: '%QUERY',
        transform: function transform(response) {
          return response.profile;
        }
      }
    });
    $select.tagsinput({
      typeaheadjs: {
        name: 'profileslist',
        displayKey: 'full_name',
        limit: 75,
        source: profileSearch.ttAdapter(),
        templates: {
          suggestion: function suggestion(profile) {
            return '<p><strong>' + profile.full_name + '</strong>, <em>' + (profile.information[0].data.title || '') + '</em></p>';
          }
        }
      },
      freeInput: false,
      itemValue: function itemValue(profile) {
        return profile.id;
      },
      itemText: function itemText(profile) {
        return profile.full_name;
      },
      onTagExists: function onTagExists(item, $tag) {
        return $tag.css({
          opacity: 0
        }).animate({
          opacity: 1
        }, 500);
      },
      // blink once
      afterSelect: function afterSelect() {
        return $select.tagsinput('input').val('');
      }
    });

    // add back existing options
    $select.find('option').each(function (i, option) {
      return $select.tagsinput('add', {
        'id': option.value,
        'full_name': option.text
      });
    });
    $select.tagsinput('input').on('typeahead:asyncrequest', function () {
      $(this).closest('.twitter-typeahead').css('background', 'no-repeat center url(' + this_url + '/img/ajax-loader.gif)');
    }).on('typeahead:asyncreceive typeahead:asynccancel', function () {
      $(this).closest('.twitter-typeahead').css('background-image', 'none');
    });
  };

  /**
   * Registers and enables any profile pickers on the page
   * 
   * @return {void}
   */
  var registerProfilePickers = function registerProfilePickers() {
    $('.profile-picker').each(function (i, picker) {
      if (picker.querySelector('select')) {
        registerProfilePicker('#' + picker.querySelector('select').id.replace('[]', '\\[\\]'));
      }
    });
  };

  /**
  * Registers and enables any tag editors on the page.
  * 
  * @return {void}
  */
  var registerTagEditors = function registerTagEditors() {
    $('.tags-editor').each(function (i, editor) {
      registerTagPicker('#' + editor.querySelector('select').id.replace('[]', '\\[\\]'));
    });
  };

  /**
   * Creates and initializes Bootstrap-Tagsinput / Typeahead.js Tag Picker.
   *
   * @param  {String} selector : CSS selector for the input field to register
   * @param  {String} api      : URL to the tag API
   * @return {void}
   */
  var registerTagPicker = function registerTagPicker(selector, api) {
    if (typeof api === 'undefined') api = this_url + '/tags/api';
    var $select = $(selector);
    if ($select.length === 0) return;
    var tagSearch = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 50,
      remote: {
        url: api + '/search?name=%QUERY',
        wildcard: '%QUERY'
      }
    });
    $select.tagsinput({
      typeaheadjs: {
        name: 'taglist',
        displayKey: 'name'.en,
        limit: 75,
        source: tagSearch.ttAdapter()
      },
      freeInput: true,
      onTagExists: function onTagExists(item, $tag) {
        return $tag.css({
          opacity: 0
        }).animate({
          opacity: 1
        }, 500);
      },
      // blink once
      afterSelect: function afterSelect() {
        return $select.tagsinput('input').val('');
      }
    });
    $select.tagsinput('input').on('typeahead:asyncrequest', function () {
      $(this).closest('.twitter-typeahead').css('background', 'no-repeat center url(' + this_url + '/img/ajax-loader.gif)');
    }).on('typeahead:asyncreceive typeahead:asynccancel', function () {
      $(this).closest('.twitter-typeahead').css('background-image', 'none');
    });
    $select.closest('.modal-content').find('.tagsInsertBtn').click(function (event) {
      postTags($select);
    });
  };

  /**
   * Posts updated tags to the API URL.
   * 
   * @param  {jQuery} $select the select element containing the tags
   * @return {void}
   */
  var postTags = function postTags($select) {
    var tags = $select.tagsinput('items');
    var formData = new FormData();
    formData.append('_token', $select.data('token'));
    formData.append('model', $select.data('model'));
    formData.append('id', $select.data('model-id'));
    for (var i = 0; i < tags.length; i++) {
      formData.append('tags[]', tags[i]);
    }
    $.ajax({
      method: "POST",
      url: $select.data('url'),
      dataType: 'json',
      processData: false,
      contentType: false,
      data: formData,
      success: function success(data, textStatus) {
        $('#' + $select.data('model-name') + '_tags_editor').modal('hide');
        $('#' + $select.data('model-name') + '_current_tags').html(data.view);
      },
      error: function error(xHr, textStatus, errorThrown) {
        toast("Error updating tags: ".concat(errorThrown), 'danger');
      }
    });
  };

  /**
   * Register playPauseVideo function for video control button(s)
   * 
   * @return {void}
   */
  var registerVideoControls = function registerVideoControls() {
    var play_pause_buttons = document.querySelectorAll('button.video-control.play-pause');
    var prefers_reduced_motion = window.matchMedia("(prefers-reduced-motion: reduce)");
    play_pause_buttons.forEach(function (bt) {
      return bt.addEventListener('click', function (evt) {
        var button = evt.currentTarget;
        var video = document.getElementById(button === null || button === void 0 ? void 0 : button.getAttribute('aria-controls'));
        if (video instanceof HTMLVideoElement && button instanceof HTMLButtonElement) {
          toggleVideoPlay(video, button);
        }
      });
    });
    if (prefers_reduced_motion.matches) {
      play_pause_buttons.forEach(function (bt) {
        return bt.click();
      });
    }
  };

  /**
   * Play/pause video and controls the video and button attributes
   * @param {HTMLVideoElement} vid 
   * @param {HTMLButtonElement} btn 
   * @return {void}
   */
  var toggleVideoPlay = function toggleVideoPlay(vid, btn) {
    var icon = btn.querySelector('[data-fa-i2svg],.fas');
    if (vid.paused) {
      vid.play();
      btn.ariaPressed = "true";
      icon.className = "fas fa-pause";
    } else {
      vid.pause();
      btn.ariaPressed = "false";
      icon.className = "fas fa-play";
    }
  };
  return {
    add_row: add_row,
    clear_row: clear_row,
    config: config,
    deobfuscate_mail_links: deobfuscate_mail_links,
    on_list_updated: on_list_updated,
    preview_selected_image: preview_selected_image,
    replace_icon: replace_icon,
    registerTagEditors: registerTagEditors,
    registerProfilePickers: registerProfilePickers,
    toast: toast,
    toggle_class: toggle_class,
    toggle_show: toggle_show,
    wait_when_submitting: wait_when_submitting,
    registerVideoControls: registerVideoControls
  };
}(jQuery);
window.profiles = profiles;
$(function () {
  // date-picker
  __webpack_require__(/*! bootstrap-datepicker */ "./node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js");
  $('.datepicker.year').datepicker(profiles.config.datepicker.year);
  $('.datepicker.month').datepicker(profiles.config.datepicker.month);

  //show preview of uploaded image
  $('input[type="file"]').on('change', function (e) {
    return profiles.preview_selected_image(e);
  });

  // enable drag and drop sorting for items with sortable class
  if ($('.sortable').length > 0) {
    Sortable.create($('.sortable')[0], {
      handle: '.handle',
      scroll: true,
      scrollSpeed: 50,
      ghostClass: 'sortable-ghost',
      onUpdate: function onUpdate(evt) {
        var _evt$target$dataset$o;
        return profiles.on_list_updated(evt.target, (_evt$target$dataset$o = evt.target.dataset.onsort) !== null && _evt$target$dataset$o !== void 0 ? _evt$target$dataset$o : '');
      }
    });
  }

  //trigger clearing of elements when trash is clicked
  $('.actions .trash').on('click', function (e) {
    profiles.clear_row(this);
  });
  $('[data-toggle="add_row"]').on('click', function (e) {
    return profiles.add_row(e);
  });
  $('.back.btn').on('click', function (e) {
    window.history.go(-1);
  });
  $('.flash-message').on('click', function () {
    $(this).hide();
  }).animate({
    opacity: 0
  }, 5000);

  //animate anchor clicks on page
  $('a[href^="#"]:not([href="#"]):not([data-scrollto-anchor="false"])').on('click', function (event) {
    var target = $($(this).attr('href'));
    if (target.length) {
      event.preventDefault();
      $('html, body').animate({
        scrollTop: target.offset().top
      }, 1000);
    }
  });

  // register tag editors if tagsinput is loaded
  if (typeof $.fn.tagsinput === 'function' && typeof Bloodhound === 'function') {
    profiles.registerTagEditors();
    profiles.registerProfilePickers();
  }
  $('[data-toggle=class]').on('click', profiles.toggle_class);
  $('[data-toggle=replace-icon]').on('click', profiles.replace_icon);
  $('[data-toggle=show]').on('change page_up', profiles.toggle_show).trigger('change');
  $('[data-evaluate=profile-eml]').each(profiles.deobfuscate_mail_links);
  $('[data-toggle="tooltip"]').tooltip();

  /**
  * Load html element as content into a popover
  */
  $('[data-toggle="popover"]').popover({
    html: true,
    content: function content() {
      var content = $(this).data("popover-content");
      return typeof content === 'string' && $(content).length ? $(content).html() : '';
    }
  });
  if (document.querySelectorAll('.video-cover video').length > 0 && document.querySelectorAll('.video-cover .video-control').length > 0) {
    profiles.registerVideoControls();
  }
});

// Livewire global hooks
if ((typeof Livewire === "undefined" ? "undefined" : _typeof(Livewire)) === 'object') {
  if ((typeof FontAwesomeDom === "undefined" ? "undefined" : _typeof(FontAwesomeDom)) === 'object') {
    document.addEventListener('DOMContentLoaded', function () {
      Livewire.hook('message.processed', function () {
        return FontAwesomeDom.i2svg();
      });
    });
  }
  Livewire.on('alert', function (message, type) {
    return profiles.toast(message, type);
  });
  Livewire.onError(function (status, response) {
    // show a toast instead of a modal for 403 responses
    if (status === 403) {
      profiles.toast('⛔️ Sorry, you are not authorized to do that.', 'danger');
      return false;
    }
  });
}

/***/ }),

/***/ "./resources/assets/js/bootstrap.js":
/*!******************************************!*\
  !*** ./resources/assets/js/bootstrap.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @fortawesome/fontawesome-svg-core */ "./node_modules/@fortawesome/fontawesome-svg-core/index.es.js");
/* harmony import */ var _fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @fortawesome/free-solid-svg-icons */ "./node_modules/@fortawesome/free-solid-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @fortawesome/free-regular-svg-icons */ "./node_modules/@fortawesome/free-regular-svg-icons/index.es.js");
/* harmony import */ var _fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fortawesome/free-brands-svg-icons */ "./node_modules/@fortawesome/free-brands-svg-icons/index.es.js");
/* provided dependency */ var __webpack_provided_window_dot_jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/src/jquery.js");
/** Load JavaScript dependencies */

window.Popper = (__webpack_require__(/*! popper.js */ "./node_modules/popper.js/dist/esm/popper.js")["default"]);

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.$ = __webpack_provided_window_dot_jQuery = __webpack_require__(/*! jquery */ "./node_modules/jquery/src/jquery.js");
  __webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.js");
  __webpack_require__(/*! bootstrap4-tagsinput */ "./node_modules/bootstrap4-tagsinput/tagsinput.js");
} catch (e) {}

/**
 * Font Awesome 5
 */




_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__.config.autoReplaceSvg = 'nest';
_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__.library.add(_fortawesome_free_solid_svg_icons__WEBPACK_IMPORTED_MODULE_1__.fas, _fortawesome_free_regular_svg_icons__WEBPACK_IMPORTED_MODULE_2__.far, _fortawesome_free_brands_svg_icons__WEBPACK_IMPORTED_MODULE_3__.fab);
// Kicks off the process of finding <i> tags and replacing with <svg>
_fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__.dom.watch();
window.FontAwesomeDom = _fortawesome_fontawesome_svg_core__WEBPACK_IMPORTED_MODULE_0__.dom;

// Sortable
window.Sortable = __webpack_require__(/*! sortablejs/Sortable */ "./node_modules/sortablejs/Sortable.js");

// Typeahead Bloodhound
window.Bloodhound = __webpack_require__(/*! corejs-typeahead */ "./node_modules/corejs-typeahead/dist/typeahead.bundle.js");

// Trix editor
__webpack_require__(/*! trix */ "./node_modules/trix/dist/trix.js");

/***/ }),

/***/ "./resources/assets/sass/app.scss":
/*!****************************************!*\
  !*** ./resources/assets/sass/app.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["css/app","/js/vendor"], () => (__webpack_exec__("./resources/assets/js/app.js"), __webpack_exec__("./resources/assets/sass/app.scss")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=app.js.map