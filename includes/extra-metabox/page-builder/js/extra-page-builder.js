// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

	// undefined is used here as the undefined global variable in ECMAScript 3 is
	// mutable (ie. it can be changed by someone else). undefined isn't really being
	// passed in so we can ensure the value of it is truly undefined. In ES5, undefined
	// can no longer be modified.

	// window and document are passed through as local variable rather than global
	// as this (slightly) quickens the resolution process and can be more efficiently
	// minified (especially when both are regularly referenced in your plugin).

	// Create the defaults once
	var pluginName = "extraPageBuilder",
		defaults = {

		};

	// The actual plugin constructor
	function Plugin ( element, options ) {
		this.element = $(element);
		// jQuery has an extend method which merges the contents of two or
		// more objects, storing the result in the first object. The first object
		// is generally empty as we don't want to alter the default options for
		// future instances of the plugin
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();

		this.block_choose = null;
		this.last_modal_block = null;
	}

	Plugin.prototype = {
		init: function () {
			// Place initialization logic here
			// You already have access to the DOM element and
			// the options via the instance, e.g. this.element
			// and this.settings
			// you can add more functions like the one below and
			// call them like so: this.yourOtherFunction(this.element, this.settings).

			this.element.on('click', '.extra-page-builder-row-admin .dodelete', function (event) {
				$(event.delegateTarget).data('extraPageBuilder').deleteRow($(this).closest('.extra-page-builder-row'));
				return false;
			});

			this.element.on('click', '.layout-selected', function (event) {
				$(event.delegateTarget).data('extraPageBuilder').toggleLayoutChooser($(this).closest('.extra-page-builder-row'));
				return false;
			});

			this.element.on('click', '.layout-choices > .layout-button', function (event) {
				$(event.delegateTarget).data('extraPageBuilder').changeLayout($(this).attr('href').substr(7), $(this).closest('.extra-page-builder-row'));
				return false;
			});

			this.element.on('click', '.choose-block .choose-link', function (event) {
				$(event.delegateTarget).data('extraPageBuilder').openBlockChooser($(this).closest('.extra-page-builder-block'));
				return false;
			});

			$(document).on('click', '.choose-block-choices .choose-block-button', function (event) {
				if (extraPageBuilder.block_choose != null) {
					extraPageBuilder.setBlockChoice(extraPageBuilder.block_choose, $(this).data('value'), $(this).data('resizable'), $(this).data('editable'));
				}
				return false;
			});

			this.element.on('click', '.delete-block', function (event) {
				$(event.delegateTarget).data('extraPageBuilder').resetBlockChoice($(this).closest('.extra-page-builder-block'));
				return false;
			});

			this.element.on('click', '.edit-block', function (event) {
				$(event.delegateTarget).data('extraPageBuilder').editBlock($(this).closest('.extra-page-builder-block'));
				return false;
			});
		},
		deleteRow: function ($row) {
			var $rowContainer = $row.parent();
			TweenMax.to($rowContainer, 0.3, {height: 0, onComplete: function () {
				$rowContainer.remove();
			}});
		},
		toggleLayoutChooser: function ($row) {
			var $layoutChoices = $row.find('.extra-page-builder-row-admin .layout-choices');

			if ($layoutChoices.hasClass('open')) {
				TweenMax.to($layoutChoices, 0.3, {width: 0});
				$layoutChoices.removeClass('open');
			} else {
				TweenMax.to($layoutChoices, 0.3, {width: 180});
				$layoutChoices.addClass('open');
			}
		},
		changeLayout: function ( layout, $row ) {
			var $rowTypeInput = $row.find('.extra-page-builder-row-type'),
				$layoutSelected = $row.find('.extra-page-builder-row-admin .layout-selected'),
				$rowContent  = $row.find('.extra-page-builder-row-content');

			$layoutSelected.find('.icon-extra-page-builder').attr('class', 'icon-extra-page-builder icon-extra-page-builder-'+layout);
			$rowContent.attr('class', 'extra-page-builder-row-content extra-page-builder-row-content-'+layout);
			$rowTypeInput.val(layout);

			this.toggleLayoutChooser($row);

			this.resetFirstAndLast($row);

			var $visibleBlocks = $row.find('.extra-page-builder-block:visible'),
				$pageBuilder = $row.closest('.extra-page-builder'),
				$currentVisibleBlock = null;
			$visibleBlocks.each(function () {
				$currentVisibleBlock = $(this);
				$pageBuilder.trigger(
					'refreshPreview.pagebuilder.extra',
					[
						$currentVisibleBlock.find('.extra-page-builder-block-choice').val(),
						$currentVisibleBlock,
						$currentVisibleBlock.find('.extra-field-form')
					]
				);
			});

			$row.trigger('layoutChange.pagebuilder.extra', [$row, layout]);
		},
		resetFirstAndLast: function ($row) {
			var $blocks = $row.find('.extra-page-builder-block'),
				$visibleBlocks = $blocks.filter(':visible'),
				i = 1,
				$currentBlock;

			$blocks.removeClass('first').removeClass('last');

			$visibleBlocks.each(function () {
				$currentBlock = $(this);
				if (i == 1) {
					$currentBlock.addClass('first');
				}
				if (i == $visibleBlocks.length) {
					$currentBlock.addClass('last');
				}
				i++;
			});
		},
		openBlockChooser: function ($block) {
			var $chooseBlock = $block.find('.choose-block'),
				$choices = $chooseBlock.find('.choose-block-choices');

			this.block_choose = $block;
			this.last_modal_block = null;
			extraAdminModal
				.options({footer:[], size: {height: '358', width: '358'}})
				.show('Choisir un bloc', $choices.clone());
		},
		setBlockChoice: function ($block, block_type, resizable, editable) {
			var $blockWrapper = $block.find('.extra-page-builder-block-wrapper'),
				$inputBlockChoice = $block.find('.extra-page-builder-block-choice'),
				blockId = $block.data('block-number'),
				$inputReference = $block.find('.extra-page-builder-block-choice'),
				rowId = $inputReference.attr('name').replace('[page_builder_block_choice_'+blockId+']', ''),
				$row = $block.closest('.extra-page-builder-row'),
				$rowLayout = $row.find('.extra-page-builder-row-type').val();
				plugin = this;

			if ($rowLayout == '') {
				$rowLayout = $row.find('.layout-choices .layout-button').first().attr('href').substr(7);
			}

			$inputBlockChoice.val(block_type);
			$block.removeClass('not-selected');
			extraAdminModal.hide();

			$.get(
				ajax_url,
				{
					action: ajax_action,
					block_type: block_type,
					block_id: blockId,
					row_id: rowId,
					row_layout: rowId
				},
				function (data) {
					console.log(data);
					$blockWrapper.html(data);

					plugin.last_modal_block = $block;
					if (resizable == 'yes') {
						$block.addClass('resizable');
						setResizable($block);
					} else {
						$block.removeClass('resizable');
					}
					if (editable == 'yes') {
						$block.addClass('editable');
						$('.extra-page-builder').trigger('showform.pagebuilder.extra', [block_type, $block, $block.find('.extra-field-form')]);
					} else {
						$block.removeClass('editable');
					}
				}
			);
		},
		enableResizable: function ($block) {
			if (!$block.hasClass('resizable')) {
				$block.addClass('resizable');
				setResizable($block);
			}
		},
		disableResizable: function ($block) {
			if ($block.hasClass('resizable')) {
				$block.resizable('destroy');
				$block.css('height', '');
				$block.removeClass('resizable');
			}
		},
		resetBlockChoice: function ($block) {
			var $blockContent = $block.find('.extra-page-builder-block-content'),
				$blockForm = $block.find('.extra-page-builder-block-form'),
				$inputBlockChoice = $block.find('.extra-page-builder-block-choice'),
				$inputBlockHeight = $block.find('.extra-page-builder-block-height');

			$block.addClass('not-selected');

			//Remove resizable
			if ($block.hasClass('resizable')) {
				$block.resizable('destroy');
				$block.css('height', '');
				$block.removeClass('resizable');
			}
			if ($block.hasClass('editable')) {
				$block.removeClass('editable');
			}

			$inputBlockChoice.val('');
			$inputBlockHeight.val('');

			$blockContent.html('');
			$blockForm.html('');
		},
		editBlock: function ($block) {
			this.last_modal_block = $block;
			$('.extra-page-builder').trigger('showform.pagebuilder.extra', [$block.find('.extra-page-builder-block-choice').val(), $block, $block.find('.extra-field-form')]);
		},
		swapBlocks: function ($block1, $block2) {
//			console.log('swap');

			var block1_number = $block1.data('block-number'),
				block2_number = $block2.data('block-number');

			/**
			 * name examples
			 * _page_builder[page_builder][0][page_builder_row_type]
			 * _page_builder[page_builder][0][extra_page_builder_image_3]
			 *
 			 */

			var block1_name = $block1.closest('.wpa_group').attr('class').match(/wpa_group-([a-zA-Z0-9_-]*)/i)[1],
				reg_block1 = new RegExp('\\['+block1_name+'\\]\\[(\\d+)\\]', 'i'),
				$block1_row = $block1.closest('.extra-page-builder-row'),
				block1_row_id = $block1_row.find('.extra-page-builder-row-type').attr('name').match(reg_block1)[1];
//			console.log('block1_row_id : '+block1_row_id);
//			console.log('block1_number : '+block1_number);

			var block2_name = $block2.closest('.wpa_group').attr('class').match(/wpa_group-([a-zA-Z0-9_-]*)/i)[1],
				reg_block2 = new RegExp('\\['+block2_name+'\\]\\[(\\d+)\\]', 'i'),
				$block2_row = $block2.closest('.extra-page-builder-row'),
				block2_row_id = $block2_row.find('.extra-page-builder-row-type').attr('name').match(reg_block2)[1];
//			console.log('block2_row_id : '+block2_row_id);
//			console.log('block2_number : '+block2_number);


			$block1.find('*').each(function(i, elem) {
				var the_prop = $(elem).attr('name');
				if (the_prop) {
					var reg_row = new RegExp('\\['+block1_name+'\\]\\[(\\d+)\\]', 'i');
					var the_row_match = the_prop.match(reg_row);

					if (the_row_match) {
						the_prop = the_prop.replace(the_row_match[0], '['+ block1_name + ']' + '['+ block2_row_id +']');
						$(elem).attr('name', the_prop);
					}

					var reg_block = new RegExp('_(\\d+)\\]', 'i');
					//console.log('the_prop : '+the_prop);
					var the_block_match = the_prop.match(reg_block);

					if (the_block_match) {
						//console.log('block1 match : '+the_prop+' with '+the_block_match[0]+' replace by '+'_'+ block2_number +']');
						the_prop = the_prop.replace(the_block_match[0], '_'+ block2_number +']');
						$(elem).attr('name', the_prop);
						//console.log('new name : '+$(elem).attr('name'));
					}
				}
			});

			$block2.find('*').each(function(i, elem) {
				var the_prop = $(elem).attr('name');
				if (the_prop) {
					var reg_row = new RegExp('\\['+block2_name+'\\]\\[(\\d+)\\]', 'i');
					var the_row_match = the_prop.match(reg_row);

					if (the_row_match) {
						the_prop = the_prop.replace(the_row_match[0], '['+ block2_name + ']' + '['+ block1_row_id +']');
						$(elem).attr('name', the_prop);
					}

					var reg_block = new RegExp('_(\\d+)\\]', 'i');
					var the_block_match = the_prop.match(reg_block);

					if (the_block_match) {
						the_prop = the_prop.replace(the_block_match[0], '_'+ block1_number +']');
						$(elem).attr('name', the_prop);
					}
				}
			});

			// Swap DOM
			var a = $block1[0],
				b = $block2[0],
				t = a.parentNode.insertBefore(document.createTextNode(''), a);
			b.parentNode.insertBefore(a, b);
			t.parentNode.insertBefore(b, t);
			t.parentNode.removeChild(t);

			// Swap block numbers
			$block1.data('block-number', block2_number);
			$block2.data('block-number', block1_number);

			$block1.removeClass('extra-page-builder-block-'+block1_number);
			$block1.addClass('extra-page-builder-block-'+block2_number);

			$block2.removeClass('extra-page-builder-block-'+block2_number);
			$block2.addClass('extra-page-builder-block-'+block1_number);


			this.resetFirstAndLast($block1_row);
			this.resetFirstAndLast($block2_row);

			// Ask refresh for blocks
			var $pageBuilder = $block1.closest('.extra-page-builder');

			console.log($block1.find('.extra-page-builder-block-choice').val());
			console.log($block2.find('.extra-page-builder-block-choice').val());

			$pageBuilder.trigger(
				'refreshPreview.pagebuilder.extra',
				[
					$block1.find('.extra-page-builder-block-choice').val(),
					$block1,
					$block1.find('.extra-field-form')
				]
			);
			$pageBuilder.trigger(
				'refreshPreview.pagebuilder.extra',
				[
					$block2.find('.extra-page-builder-block-choice').val(),
					$block2,
					$block2.find('.extra-field-form')
				]
			);



			return this;

		}
	};

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[ pluginName ] = function ( options ) {
		this.each(function() {
			if ( !$.data( this, pluginName ) ) {
				$.data( this, pluginName, new Plugin( this, options ) );
			}
		});

		// chain jQuery functions
		return this;
	};

})( jQuery, window, document );

function setDraggable($blocks) {
	$blocks.draggable({
		cursor: 'move',
		handle: '.extra-page-builder-block-content-admin',
		appendTo: jQuery('body'),
		//iframeFix: true,
		helper: function () {
			var $this = jQuery(this),
				$clone = $this.find('.extra-page-builder-block-content').clone();
			$clone.css('height', $this.height());
			$clone.css('width', $this.width());

			$clone.addClass('.extra-page-builder-block-content-helper');

			return $clone;
		},
		start: function (event, ui) {
			jQuery('.extra-page-builder-block.ui-draggable-dragging').css('width', jQuery(this).outerWidth());
			jQuery(this).addClass('extra-page-builder-block-dragging');
		},
		stop: function (event, ui) {
			jQuery(this).removeClass('extra-page-builder-block-dragging');
		}
	});
}

function setDroppable($blocks) {
	$blocks.droppable({
		accept: '.extra-page-builder-block',
		tolerance: 'pointer',
		activeClass: 'extra-page-builder-block-active',
		hoverClass: 'extra-page-builder-block-hover',
		drop: function (event, ui) {
			extraPageBuilder.swapBlocks(ui.draggable, jQuery(this));
		}
	});
}

function setResizable($blocks) {

	var $resizableBlocks = $blocks.filter('.resizable'),
		brotherHeights = null;
	$resizableBlocks.resizable({
		disabled: false,
		handles: "s",
		start: function (event, ui) {
			var $this = jQuery(this),
				$row = $this.closest('.extra-page-builder-row');
			$this.css('position', 'relative');
			$this.css('top', '');
			$this.css('left', '');

			//$this.find('.extra-page-builder-block-content-admin').css('display', 'none');
			$row.addClass('resizing');

			brotherHeights = [];
			$row.find('.extra-page-builder-block').each(function() {
				var $current = jQuery(this),
					brotherHeight = $current.height();
				if ($current[0] != $this[0] && !$current.is(':hidden')) {
					brotherHeights.push(brotherHeight);
					$current.find('.extra-page-builder-block-content-admin-size').html(brotherHeight+' px');
				}
			});

		},
		stop: function (event, ui) {
			var $this = jQuery(this),
				$block = $this.closest('.extra-page-builder-block'),
				$pageBuilder = $block.closest('.extra-page-builder'),
				currentheight = $block.css('height'),
				$input = $block.find('.extra-page-builder-block-height');
			$input.val(currentheight);

			$pageBuilder.trigger(
				'refreshPreview.pagebuilder.extra',
				[
					$block.find('.extra-page-builder-block-choice').val(),
					$block,
					$block.find('.extra-field-form')
				]
			);

			$this.closest('.extra-page-builder-row').removeClass('resizing');
		},
		resize: function (event, ui) {
			var $this = jQuery(this),
				top = jQuery(window).scrollTop(),
				diff = top + jQuery(window).height() - event.pageY,
				currentHeight = $this.height(),
				closestBrotherHeight = null,
				closestBrotherHeightDiff = null,
				currentBrotherHeight = null,
				currentBrotherHeightDiff = null,
				i = 0;

			while (i < brotherHeights.length) {
				currentBrotherHeight = brotherHeights[i];
				currentBrotherHeightDiff = Math.abs(currentHeight - currentBrotherHeight);

				if (closestBrotherHeightDiff == null || (currentBrotherHeightDiff < closestBrotherHeightDiff)) {
					closestBrotherHeightDiff = currentBrotherHeightDiff;
					closestBrotherHeight = currentBrotherHeight;
				}
				i++;
			}

			if (closestBrotherHeightDiff < 8) {
				$this.css('height', closestBrotherHeight);
			}

			$this.closest('.extra-page-builder-block').find('.extra-page-builder-block-content-admin-size').html($this.height()+' px');

			if(diff < 50) {
				jQuery(window).scrollTop(top + 50 - diff);
			}

		}
	}).find('.resizable')
		.css({overflow:'auto',
			width:'100%',
			height:'100%'});

	jQuery('.extra-page-builder-block .ui-resizable-handle.ui-resizable-s').css('display', '');
}

var extraAdminModal,
	extraPageBuilder;

jQuery(document).ready(function($){
	$('body').append('<div id="extra-admin-modal-container"></div>');
	extraAdminModal = $('#extra-admin-modal-container').extraAdminModal().data('extraAdminModal');
	extraPageBuilder = $('.extra-page-builder').extraPageBuilder().data('extraPageBuilder');

	/**************************
	 *
	 * DEFAULT EDIT BLOCK BEHAVIOUR
	 *
	 *************************/
	$(document).on('showform.pagebuilder.extra', function (event, block_type, $block, $form) {
		extraAdminModal.show('Modifier le bloc',  $form);
	});
	$(document).on('close.adminmodal.extra', function (event, $form) {
		if (extraPageBuilder.last_modal_block != null && $form != null) {
			$('.extra-page-builder').trigger(
				'hideForm.pagebuilder.extra',
				[
					extraPageBuilder.last_modal_block.find('.extra-page-builder-block-choice').val(),
					extraPageBuilder.last_modal_block,
					$form
				]
			);
		}
	});
	$(document).on('hideForm.pagebuilder.extra', function (event, block_type, $block, $form) {
		extraPageBuilder.last_modal_block.find('.extra-page-builder-block-form').append($form);
		$('.extra-page-builder').trigger(
			'review.pagebuilder.extra',
			[
				extraPageBuilder.last_modal_block.find('.extra-page-builder-block-choice').val(),
				extraPageBuilder.last_modal_block,
				$block.find('.extra-field-form')
			]
		);
	});
	$(document).on('save.adminmodal.extra', function (event, $form) {
		extraAdminModal.hide();
	});


	// DRAG N DROP FOR ROWS
	$('.wpa_loop').sortable({
		forcePlaceholderSize: true,
		placeholder: 'extra-page-builder-row-placeholder',
		opacity: 1,
		handle: '.extra-page-builder-row-admin > .grip'
	});

	// DRAG N DROP FOR BLOCKS
	var $blocks = $('.extra-page-builder-block');

	setDraggable($blocks);
	setDroppable($blocks);
	setResizable($blocks);

	if ($.wpalchemy !== undefined) {
		$.wpalchemy.bind('wpa_copy', function(e, elmt){
			var $inner_blocks = $(elmt).find('.extra-page-builder-block');

			setDraggable($inner_blocks);
			setDroppable($inner_blocks);
			setResizable($inner_blocks);
		});
	}

	// SCROLL TO NEW ROW
	$(document).on('click', '.docopy-page_builder', function() {
		console.log('click');
		var $new = $('.wpa_loop-page_builder .wpa_group:not(.tocopy)').last(),
			newTop = $new.offset().top + 232;
		console.log(newTop);
		$(window).scrollTop(newTop);
		//TweenMax.to($(window), 0.5, {scrollTo: {y: newTop}});
	});

});
