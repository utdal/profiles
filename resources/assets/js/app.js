/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.this_url = window.this_url || '';

/** Global Profiles Module */
var profiles = (function ($, undefined) {

    /** @type {string} the current URL */
    var this_url = window.this_url;

    /** @type {Object} config settings */
    let config = {
        datepicker: {
            year: {
                autoclose: true,
                assumeNearbyYear: true,
                clearBtn: true,
                forceParse: false,
                keepEmptyValues: true,
                minViewMode: 2,
                format: 'yyyy',
            },
            month: {
                autoclose: true,
                assumeNearbyYear: true,
                clearBtn: true,
                forceParse: false,
                keepEmptyValues: true,
                minViewMode: 1,
                format: 'yyyy/mm',
            },
        },
    };

    /**
     * Checks to see if an input is empty.
     *
     * @param {HTMLElement} input
     * @return {boolean}
     */
    var _input_is_empty = function(input) {
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
    }

    /**
     * Sets img src to selected file object
     *
     * @param {Event} event the triggered event
     */
    const preview_selected_image = function (event) {
        const file_input = event.target;
        let id = file_input.id.replace(/\[/g, '\\[').replace(/\]/g, '\\]');
        $(`label[for="${id}"]`)
            .addClass('active')
            .text(file_input.files[0].name);
        $(`#${id}-img`).attr('src', window.URL.createObjectURL(file_input.files[0]));
        $(file_input).siblings('.invalid-feedback').removeClass('d-block');
    };

    /**
     * Reindex numbers in field ids, names, and labels to match sort order,
     * e.g. if the first item's name is "thing[3]", rename it to "thing[0]"
     * 
     * @param {NodeList} list_items - the ordered list of items to reindex
     */
    const reindex_sorted_list = (list_items) => {
        for (let i = 0; i < list_items.length; i++) {
            if (list_items[i].dataset.rowId) {
                list_items[i].dataset.rowId = i;
            }
            const search_for = /\[\d+\]/g;
            const replace_with = `[${i}]`;
            for (const field of list_items[i].querySelectorAll('[name]')) {
                field.name = field.name.replace(search_for, replace_with);
            }
            for (const field of list_items[i].querySelectorAll('[id]')) {
                field.id = field.id.replace(search_for, replace_with);
            }
            for (const field of list_items[i].querySelectorAll('label[for]')) {
                field.htmlFor = field.htmlFor.replace(search_for, replace_with);
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
    const on_list_updated = (el, actions) => {
        if (typeof actions !== 'string') { return; }
        for (const action of actions.split(',').map(i => i.trim())) {
            if (action === 'reindex') {
                reindex_sorted_list(el.children);
            }
    
            if (action === 'reset-next-row-id') {
                el.dataset.nextRowId = String((Number(el.dataset.nextRowId) > 0) ? el.children.length : -1);
            }
        }
    }

    /**
     * Display spinner animation and disable form submit button
     *
     * @param {HTMLElement} form that gets submitted 
     */
    const wait_when_submitting = function(form) {
        elem = form.querySelector('button[type=submit]');

        elem_text = elem.innerHTML.replace(/<i[^>]*>(.*?)<\/i>/g, '');
        elem.innerHTML = `<i class="fas fa-spinner fa-spin fa-fw"></i> ${elem_text}`;

        elem.classList.add('btn-primary', 'disabled');
        elem.classList.remove('btn-light', 'btn-dark', 'btn-secondary', 'btn-info', 'btn-success', 'btn-warning', 'btn-danger');
        elem.disabled = true;
    }

    /**
     * Adds a new item input row
     *
     * @param {Event} event the triggered event
     * @this {HTMLElement} the DOM element that was clicked
     */
    const add_row = function(event) {
        const options = event.target.dataset;
        const item_template = document.querySelector(options.template ?? 'form .record');
        const item_container = document.querySelector(options.insertInto) ?? item_template.parentElement;

        if (item_template) {
            const old_id = item_template.dataset.rowId;
            let new_id;
            if (Number(item_container.dataset.nextRowId) >= 0) {
                new_id = String(item_container.dataset.nextRowId++);
            } else {
                new_id = String(item_container.dataset.nextRowId--);
            }
            let new_item = item_template.cloneNode(true);
            new_item.dataset.rowId = new_id;

            new_item.querySelectorAll('input:not([type="button"]), textarea, select')?.forEach((el) => {
                el.id = el.id.replace(old_id, new_id);
                el.setAttribute('name', el.name.replace(old_id, new_id));
                el.setAttribute('value', '');
                el.value = '';
            });
            new_item.querySelectorAll(`input[type="hidden"][name$="\[id\]"]`)?.forEach((el) => {
                el.id = el.name;
                el.value = new_id;
            });
            new_item.querySelectorAll('label')?.forEach((el) => {
                el.setAttribute('for', el.getAttribute('for')?.replace(old_id, new_id));
            });
            new_item.querySelectorAll('trix-editor')?.forEach((el) => {
                el.setAttribute('input', el.getAttribute('input').replace(old_id, new_id));
            });
            new_item.querySelectorAll('img')?.forEach((el) => {
                el.id = el.id.replace(old_id, new_id);
                el.src = '';
            });
            new_item.querySelectorAll('.custom-file-label')?.forEach((el) => {
                el.id = el.id.replace(old_id, new_id);
                el.innerHTML = 'Select an image';
            });
            new_item.querySelectorAll('.actions .trash')?.forEach((el) => {
                $(el).on('click', () => clear_row(el));
            });
            new_item.querySelectorAll('input[type="file"][accept^="image"]')?.forEach((el) => {
                $(el).on('change', (event) => preview_selected_image(event));
            });
            new_item.querySelectorAll('.datepicker.year')?.forEach((el) => {
                $(el).datepicker(config.datepicker.year);
            });
            new_item.querySelectorAll('.datepicker.month')?.forEach((el) => {
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
    }

    /**
     * Clears an input text or textarea row
     *
     * @param {HTMLElement} elem
     */
    var clear_row = function (elem) {
        parent_elem = $(elem).parent().parent();
        parent_elem.slideUp().find("input[type=text], input[type=url], input[type=month], input.clearable, textarea, select").val('');

        const list_container = parent_elem[0].parentElement;

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
    var toggle_class = function (evt) {
        var $this = $(this);
        var $target = $this.data('target') ? $($this.data('target')) : $this;
        $target.toggleClass($this.data('toggle-class'));
    }

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
    var replace_icon = function (evt) {
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
    }

    /**
     * Display a dynamic toast alert
     * 
     * @param {String} message - the message to display
     * @param {String} type - alert type, e.g. primary, success, warning, danger, and etc.
     */
    let toast = (message, type) => {
      let flash_container = document.querySelector('.flash-container');

      if (!flash_container) {
        flash_container = document.createElement('div');
        flash_container.classList = 'flash-container';
        document.body.appendChild(flash_container);
      }

      let flash_message = document.createElement('div');
      flash_message.classList = 'flash-message alert-dismissable alert-' + (type || 'success');
      flash_message.setAttribute('role', 'alert');
      flash_message.setAttribute('aria-live', 'assertive');
      flash_message.setAttribute('aria-atomic', 'true');
      flash_message.innerHTML = message;

      flash_container.appendChild(flash_message);
      
      flash_message.addEventListener('click', (e) => {e.target.style.display = 'none'});
      $(flash_message).animate({opacity: 0}, {
          duration: 5000,
          complete: () => flash_message.style.display = 'none',
      });
    }

    /**
     * Deobfuscate an email address
     * 
     * @param {String} obfuscated_mail_address - the obfuscated
     * @see App\Helpers\Utils for obfuscation strategy
     */
    let deobfuscate_mail = (obfuscated_mail_address) => {
        return obfuscated_mail_address
            .replace(/[a-z]/gi, (c) => String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26))
            .replace('☄️', '@').split('@').reverse().join('@');
    }

    /**
     * Deobfuscate email address HTML links
     *
     * @param {HTMLElement} i - the element index
     * @param {HTMLElement} el - the DOM element
     */
    let deobfuscate_mail_links = (i, el) => {
        el.innerText = deobfuscate_mail(el.id);
        el.href = "mailto:" + el.innerText;
    }

    var toggle_show = function (evt) {
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
    }

    /**
     * Creates and initializes Bootstrap-Tagsinput / Typeahead.js Profile Picker.
     *
     * @param  {String} selector : CSS selector for the input field to register
     * @param  {String} api      : URL to the profile API
     * @return {void}
     */
    let registerProfilePicker = (selector, api) => {
      if (typeof (api) === 'undefined') api = this_url + '/api/v1?with_data=1&data_type=information&public=1';
      let $select = $(selector);
      if ($select.length === 0) return;

      if ($select.data('school')) {
          api += '&from_school=' + $select.data('school');
      }

      if ($select.data('accepting-undergrad')) {
          api += '&accepting_undergrad=' + $select.data('accepting-undergrad');
      }

      let profileSearch = new Bloodhound({
        datumTokenizer: (profiles) => Bloodhound.tokenizers.whitespace(profiles.value),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 50,
        remote: {
          url: api + '&search_names=%QUERY',
          wildcard: '%QUERY',
          transform: (response) => response.profile,
        }
      });

      $select.tagsinput({
        typeaheadjs: {
          name: 'profileslist',
          displayKey: 'full_name',
          limit: 75,
          source: profileSearch.ttAdapter(),
          templates: {
            suggestion: (profile) => '<p><strong>' + profile.full_name + '</strong>, <em>' + (profile.information[0].data.title || '') + '</em></p>',
          }
        },
        freeInput: false,
        itemValue: (profile) => profile.id,
        itemText: (profile) => profile.full_name,
        onTagExists: (item, $tag) => $tag.css({opacity: 0}).animate({opacity: 1}, 500), // blink once
        afterSelect: () => $select.tagsinput('input').val(''),
      });

      // add back existing options
      $select.find('option').each((i, option) => $select.tagsinput('add', {
          'id': option.value,
          'full_name': option.text,
        }));

      $select.tagsinput('input')
        .on('typeahead:asyncrequest', function () {
          $(this).closest('.twitter-typeahead').css('background', 'no-repeat center url(' + this_url + '/img/ajax-loader.gif)');
        })
        .on('typeahead:asyncreceive typeahead:asynccancel', function () {
          $(this).closest('.twitter-typeahead').css('background-image', 'none');
        });
    }

    /**
     * Registers and enables any profile pickers on the page
     * 
     * @return {void}
     */
    let registerProfilePickers = () => {
      $('.profile-picker').each((i, picker) => {
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
    var registerTagEditors = function () {
        $('.tags-editor').each(function (i, editor) {
            registerTagPicker('#' + editor.querySelector('select').id.replace('[]', '\\[\\]'));
        });
    }

	/**
	 * Creates and initializes Bootstrap-Tagsinput / Typeahead.js Tag Picker.
	 *
	 * @param  {String} selector : CSS selector for the input field to register
	 * @param  {String} api      : URL to the tag API
	 * @return {void}
	 */
    var registerTagPicker = function (selector, api) {
        if (typeof (api) === 'undefined') api = this_url + '/tags/api';
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
                source: tagSearch.ttAdapter(),
            },
            freeInput: true,
            onTagExists: (item, $tag) => $tag.css({opacity: 0}).animate({opacity: 1}, 500), // blink once
            afterSelect: () => $select.tagsinput('input').val(''),
        });

        $select.tagsinput('input')
            .on('typeahead:asyncrequest', function () {
                $(this).closest('.twitter-typeahead').css('background', 'no-repeat center url(' + this_url + '/img/ajax-loader.gif)');
            })
            .on('typeahead:asyncreceive typeahead:asynccancel', function () {
                $(this).closest('.twitter-typeahead').css('background-image', 'none');
            });

        $select.closest('.modal-content').find('.tagsInsertBtn').click(function (event) {
            postTags($select);
        });
    }

	/**
	 * Posts updated tags to the API URL.
	 * 
	 * @param  {jQuery} $select the select element containing the tags
	 * @return {void}
	 */
    var postTags = function ($select) {
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
            success: function (data, textStatus) {
                $('#' + $select.data('model-name') + '_tags_editor').modal('hide');
                $('#' + $select.data('model-name') + '_current_tags').html(data.view);
            },
            error: function (xHr, textStatus, errorThrown) {
                toast(`Error updating tags: ${errorThrown}`, 'danger');
            },
        });
    }

    /**
     * Register playPauseVideo function for video control button(s)
     * 
     * @return {void}
     */
    const registerVideoControls = function () {
        const play_pause_buttons = document.querySelectorAll('button.video-control.play-pause');
        const prefers_reduced_motion = window.matchMedia(`(prefers-reduced-motion: reduce)`);

        play_pause_buttons.forEach(bt => bt.addEventListener('click', evt => {
            const button = evt.currentTarget;
            const video = document.getElementById(button?.getAttribute('aria-controls'));
            if (video instanceof HTMLVideoElement && button instanceof HTMLButtonElement) {
                toggleVideoPlay(video, button);
            }
        }));

        if (prefers_reduced_motion.matches) {
            play_pause_buttons.forEach((bt) => bt.click());
        }
    }

    /**
     * Play/pause video and controls the video and button attributes
     * @param {HTMLVideoElement} vid 
     * @param {HTMLButtonElement} btn 
     * @return {void}
     */
    const toggleVideoPlay = function (vid, btn) {
        const icon = btn.querySelector('[data-fa-i2svg],.fas');
        if (vid.paused) {
            vid.play();
            btn.ariaPressed = "true";
            icon.className = "fas fa-pause";
        } else {
            vid.pause();
            btn.ariaPressed = "false";
            icon.className = "fas fa-play";
        }
    }

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
        wait_when_submitting : wait_when_submitting,
        registerVideoControls: registerVideoControls,
    };

})(jQuery);

window.profiles = profiles;

$(function() {

    // date-picker
    require('bootstrap-datepicker');
    $('.datepicker.year').datepicker(profiles.config.datepicker.year);
    $('.datepicker.month').datepicker(profiles.config.datepicker.month);

    //show preview of uploaded image
    $('input[type="file"]').on('change', (e) => profiles.preview_selected_image(e));

    // enable drag and drop sorting for items with sortable class
    if ($('.sortable').length > 0) {
        Sortable.create($('.sortable')[0], {
            handle: '.handle',
            scroll: true,
            scrollSpeed: 50,
            ghostClass: 'sortable-ghost',
            onUpdate: (evt) => profiles.on_list_updated(evt.target, evt.target.dataset.onsort ?? ''),
        });
    }

  //trigger clearing of elements when trash is clicked
	$('.actions .trash').on('click', function(e) {
		  profiles.clear_row(this);
	});

    $('[data-toggle="add_row"]').on('click', (e) => profiles.add_row(e));

	$('.back.btn').on('click', function(e) {
		  window.history.go(-1);
	});

  $('.flash-message').on('click', function(){
      $(this).hide();
  }).animate({
      opacity: 0
  }, 5000);

  //animate anchor clicks on page
  $('a[href^="#"]:not([href="#"]):not([data-scrollto-anchor="false"])').on('click', function(event) {

      var target = $( $(this).attr('href') );
      if( target.length ) {
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
    content: function () {
      const content = $(this).data("popover-content");
      return (typeof content === 'string' && $(content).length) ? $(content).html() : '';
    }
  });

  if (document.querySelectorAll('.video-cover video').length > 0 && document.querySelectorAll('.video-cover .video-control').length > 0) {
    profiles.registerVideoControls();
  }

});

// Livewire global hooks
if (typeof Livewire === 'object') {
  if (typeof FontAwesomeDom === 'object') {
    document.addEventListener('DOMContentLoaded', () => {
      Livewire.hook('message.processed', () => FontAwesomeDom.i2svg());
    });
  }
  Livewire.on('alert', (message, type) => profiles.toast(message, type));
  Livewire.onError((status, response) => {
    // show a toast instead of a modal for 403 responses
    if (status === 403) {
      profiles.toast('⛔️ Sorry, you are not authorized to do that.', 'danger');
        return false;
    }
  });
}


