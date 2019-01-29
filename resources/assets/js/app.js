
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

    /**
     * Checks to see if an input is empty.
     *
     * @param {HTMLElement} input
     */
    var _input_is_empty = function(input) {
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
     * Clears an input text or textarea row
     *
     * @param {HTMLElement} elem
     */
    var clear_row = function (elem) {
        parent_elem = $(elem).parent().parent();
        parent_elem.hide().find("input[type=text], input[type=url], input[type=month], input.clearable, textarea, select").val('');
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
     * Optional additional classes on the new icon may be specified in the [data-newiconclasses=] attribute.
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
        var existing_icon = target.querySelector('svg');
        var replacement_icon = FontAwesome.icon({
            prefix: 'fas',
            iconName: this.dataset.newicon,
        }, {
            classes: this.dataset.newiconclasses ? this.dataset.newiconclasses.split(' ') : [],
        }).node[0];

        target.replaceChild(replacement_icon, existing_icon);

        // this shouldn't be needed, but for some reason Chrome occasionally fails
        // to propogate when a submit button is clicked.
        if (this.getAttribute('type') === 'submit') {
            $(this).closest('form').submit();
        }
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
            afterSelect: function() {
                $select.tagsinput('input').val('');
            },
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
                alert(textStatus + ': ' + errorThrown);
            },
        });
    }

    return {
        clear_row: clear_row,
        toggle_class: toggle_class,
        toggle_show: toggle_show,
        replace_icon: replace_icon,
        registerTagEditors: registerTagEditors,
    };

})(jQuery);

window.profiles = profiles;

$(document).ready(function() {

  $('.datepicker.year').datepicker({
      autoclose: true,
      assumeNearbyYear: true,
      clearBtn: true,
      forceParse: false,
      keepEmptyValues: true,
      minViewMode: 2,
      format: 'yyyy'
  });

  $('.datepicker.month').datepicker({
      autoclose: true,
      assumeNearbyYear: true,
      clearBtn: true,
      forceParse: false,
      keepEmptyValues: true,
      minViewMode: 1,
      format: 'yyyy/mm'
  });

  //show preview of uploaded image
	$('input[type="file"]').on('change', function(){
				$('label[for="' + this.id + '"]').addClass('active').text(this.files[0].name);
				$('#' + this.id + '-img').attr('src', window.URL.createObjectURL(this.files[0]));
	});

  //enable drag and drop sorting for items with sotable class
	if($('.sortable').length > 0){
		  Sortable.create($('.sortable')[0], {
    			handle: '.handle',
    			scroll: true,
    			scrollSpeed: 50,
    			ghostClass: "sortable-ghost"
		  });
	}

  //trigger clearing of elements when trash is clicked
	$('.actions .trash').on('click', function(e) {
		  profiles.clear_row(this);
	});

	$('.back.btn').on('click', function(e) {
		  window.history.go(-1);
	});

  $('.flash-message').on('click', function(){
      $(this).hide();
  }).animate({
      opacity: 0
  }, 5000);

  //animate anchor clicks on page
  $('a[href^="#"]:not([href="#"])').on('click', function(event) {

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
  }

  $('[data-toggle=class]').on('click', profiles.toggle_class);
  $('[data-toggle=replace-icon]').on('click', profiles.replace_icon);
  $('[data-toggle=show]').on('change page_up', profiles.toggle_show).trigger('change');

});
