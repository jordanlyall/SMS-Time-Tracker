/**
 * jQuery.labelify - Display in-textbox hints
 * Stuart Langridge, http://www.kryogenix.org/
 * Released into the public domain
 * Date: 25 June 2008
 * Last update: 25 June 2009
 * @author Stuart Langridge
 * @author Garrett LeSage
 * @author Glenn Sidney
 * @version 2.0.3
 */

/**
 * Defaults to taking the in-field label from the field's title attribute
 * @example jQuery("input").labelify();
 * @param {object|string} [settings] optional parameters to pass
 * @config {string} [text] "title" to use the field's title attribute (default),
 *                         "label" to use its <label> (for="fieldid" must be specified)
 * @config {string} [labeledClass] class applied to the field when it has label text
 *
 * @example jQuery('input').labelify('hasLabel'); // return true if the field has a label
 */
(function($){
	$.fn.labelify = function(settings) {
	  // if the element has a label, return true when 'hasLabel' is passed as an arg
	  if (typeof settings === 'string' && settings === 'hasLabel') {
	    return jQuery(this).data('hasLabel');
	  }

	  var showLabel, hideLabel, labeledClass,
	      lookups, lookup,
	      $labelified_elements,
				$settings;

	  $settings = $.extend({
	    text: 'title',
	    labeledClass: ''
	  }, settings);

	  // Compatibility with version 1.3 and prior (double-ls)
	  if ($settings.labelledClass) { $settings.labeledClass = $settings.labelledClass; }


	  lookups = {
	    title: function(input) {
	      return $(input).attr('title');
	    },
	    label: function(input) {
	      return $("label[for=" + input.id +"]").text();
	    }
	  };

	  $labelified_elements = $(this);

	  showLabel = function(el){
	    $(el).addClass($settings.labeledClass).data('hasLabel', true);
	    el.value = $(el).data("label");
	  };
	  hideLabel = function(el){
	    el.value = '';
	    $(el).removeClass($settings.labeledClass).data('hasLabel', false);
	  };
		labeledClass = function(el){
			$settings.labeledClass;
		}

	  return $(this).each(function() {
	    var $item = $(this),
	        removeValuesOnExit;

	    if (typeof $settings.text === 'string') {
	      lookup = lookups[$settings.text]; // what if not there?
	    } else {
	      lookup = $settings.text; // what if not a fn?
	    }

	    // bail if lookup isn't a function or if it returns undefined
	    if (typeof lookup !== "function" || !lookup(this)) { return; }

	    $item.bind('focus.label',function() {
	      if (this.value.replace(/\n/g, "\r") === $(this).data("label")) { hideLabel(this); }
	    }).bind('blur.label',function(){
	      if (this.value === '') { showLabel(this); }
	    }).data('label',lookup(this).replace(/\n/g,'')); // strip label's newlines
    
	    removeValuesOnExit = function() {
	      $labelified_elements.each(function(){
	        if (this.value.replace(/\n/g, "\r") === $(this).data("label")) { hideLabel(this); }
	      });
	    };
    
	    $item.parents("form").submit(removeValuesOnExit);
	    $(window).unload(removeValuesOnExit);
    
	    if (this.value !== '') {
	      // user started typing; don't overwrite his/her text!
	      return;
	    }

	    // set the defaults
	    showLabel(this);
	  });
	};

	// Rewritten serializeArray to strip out labelify's labels
	$.fn.extend({
		serializeArray: function() {
			return this.map(function(){
				return this.elements ? jQuery.makeArray(this.elements) : this;
			})
			.filter(function(){
				return this.name && !this.disabled &&
					(this.checked || /select|textarea/i.test(this.nodeName) ||
						/text|hidden|password|search/i.test(this.type));
			})
			.map(function(i, elem){
				// Changes from original start here
				var j_this = jQuery(this);
				var val = j_this.labelify('hasLabel') ? '' : j_this.val();
				// Changes from original end here

				return val == null ? null :
					jQuery.isArray(val) ?
						jQuery.map( val, function(val, i){
							return {name: elem.name, value: val};
						}) :
						{name: elem.name, value: val};
			}).get();
		}
	});
})(jQuery);