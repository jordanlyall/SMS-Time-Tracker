/*
 * jQuery Plugin: Multi Row Input
 * Version 1.0
 *
 * Copyright (c) 2010 Tom Holder Simpleweb Ltd (http://simpleweb.co.uk)
 * Licensed jointly under the GPL and MIT licenses,
 * choose which one suits your project best!
 *	
 * Built for our product ContactZilla (http://contactzilla.com/) - check it out!
 * 
 * Please feel free to use this code in your projects, just respect the license and retain this header.
 */
(function($){
	function log() {
		if (window.console && console.log) {
			for (var i = 0, len = arguments.length; i < len; i++) {
				console.log(arguments[i]);
			}
		}
	};
	$.fn.multiRowInput = function(options) {
	
		var defaults = { 
			inputRow: "div.inputRow",
			hideFirstDelete: true,
			tipTip: false,
			labelify: false,
			advanceSelect: true,
			beforeAdd: function() {},
			beforeDelete: function(){},
			afterAdd: function() {},
			afterDelete: function(){}
		};

		var opts = $.extend(defaults, options);
		
		return this.each(function(){

			var multiRowContainer = $(this);
			
			//If something gets taken out, we don't want to post it, so remove it on form submittal.
			$('form').submit(function() {
				$(opts.inputRow + ':hidden', multiRowContainer).remove();
			});

			//Wanted to do this with a :not(first) in a single line, but for some reason, it doesn't work when in a lightbox! Odd.
			//$(opts.inputRow + ':not(.hasData)', multiRowContainer).hide();
			//$(opts.inputRow, multiRowContainer).filter(':first').show();
			if (opts.hideFirstDelete) {
				$(opts.inputRow + ' a.delete', multiRowContainer).filter(':first').hide();
			}
			
			//This is due to tipTip not being able to apply itself to dynamically added elements.
			if(opts.tipTip) {
				var addTitle = $(opts.inputRow + ' a.add', multiRowContainer).filter(':first').attr('title');
				var removeTitle = $(opts.inputRow + ' a.delete', multiRowContainer).filter(':first').attr('title');
			}
			
			applyEvents(multiRowContainer);
		
			function applyEvents(scope) {
		
				//This is due to stupid tipTip not being able to apply itself to dynamically added elements.
				$('a.add',scope).attr('title',addTitle);
				$('a.delete',scope).attr('title',removeTitle);
				if(opts.tipTip) {
					$('a.add,a.delete',scope).tipTip();
				}
				
				$(opts.inputRow + ':visible:not(:last) a.add', scope).hide();
				$(opts.inputRow + ':visible:not(:last) a.delete', scope).hide();
				
				if($(opts.inputRow + ' a.delete', scope).length>1) {
					$(opts.inputRow + ' a.delete', scope).filter(':last').show();
				}

				$('a.add', scope).click(function() {
					if (opts.beforeAdd.call(this) === false) {
						return false;
					}
					if(opts.tipTip) {
						$('#tiptip_holder').hide();
					}
					
					//If no next row, add one.
					if($(this).parents(opts.inputRow).next().length === 0) {
						
						var newRow = $(this).parents(opts.inputRow).clone();
						
						newRow.appendTo(multiRowContainer);

/* 31MAY2012 - BOF - s17 - increment numbered input 'name' attributes */
						$('input',newRow).each(function(){
							var nameAttr = $(this).attr('name');
							pattern = /^(.*_)(\d+)$/;
							match = nameAttr.match(pattern);
							id = ( parseInt(match[2]) + 1);
							$(this).attr('name',match[1]+id);
						});
/* 31MAY2012 - eOF - s17 - increment numbered input 'name' attributes */

						//Give the new label and corresponding fields new IDs and labelify anything necessary.
						$('label', newRow).each(function() {
							var forElement = $(this).attr('for');
							var newID = forElement+$(opts.inputRow, multiRowContainer).length;
							$(this).attr('for',newID).attr('id',newID+'Label');
							$('#'+forElement, newRow).attr('id', newID);
							
							if(opts.labelify && $(this).hasClass('labelify')) {
								$('#'+newID, newRow).val('');
								$('#'+newID).labelify({ text: "label", labelledClass: "labelled" });
							}
							
						});
						
						
						// Want this in, but it's applying to all
						//$('select option:selected', newRow).removeAttr('selected');
						
						//if(opts.advanceSelect && $(opts.inputRow, multiRowContainer).length <= $('select option', newRow).length) {
						//	$('select', newRow)[0].selectedIndex = $(opts.inputRow, multiRowContainer).length-1;
						//}
						
						applyEvents(newRow);
						
						opts.afterAdd.call(this);
					
					} else {
						$(this).parents(opts.inputRow).next().show();
					}	
						
					$(opts.inputRow + ':visible:not(:last) a.add', multiRowContainer).hide();
					$(opts.inputRow + ':visible:not(:last) a.delete', multiRowContainer).hide();
					$(opts.inputRow + ' a.delete', multiRowContainer).filter(':last').show();
		
					return false;
				});
		
				$('a.delete', scope).click(function() {
					if (opts.beforeDelete.call(this) === false) {
						return false;
					}
					if(opts.tipTip) {
						$('#tiptip_holder').hide();
					}
					
					$(this).parents(opts.inputRow).hide();
					
					$('a.add, a.delete', $(this).parents(opts.inputRow).prev()).show();
					
					//If we're removing the second item.
					if($(this).parents(opts.inputRow).prev().prev().length===0) {
						$('a.delete', $(this).parents(opts.inputRow).prev()).hide();
					}
					
					opts.afterDelete.call(this);
					
					return false;
				});
			}
		});
	};
})(jQuery);
