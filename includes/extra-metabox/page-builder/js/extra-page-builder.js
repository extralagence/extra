var extraAdminModal;

jQuery(document).ready(function($){
	$('body').append('<div id="extra-admin-modal-container"></div>');
	extraAdminModal = $('#extra-admin-modal-container').extraAdminModal().data('extraAdminModal');

	/**************************
	 *
	 * DELETE ROW
	 *
	 *************************/
	$(document).on('click', '.extra-page-builder .extra-page-builder-row-admin .dodelete', function () {
		var $this = $(this),
			$row = $this.closest('.extra-page-builder-row'),
			$rowContainer = $row.parent();

		TweenMax.to($rowContainer, 0.3, {height: 0, onComplete: function () {
			$rowContainer.remove();
		}});

		return false;
	});

	/**************************
	 *
	 * CHOOSE LAYOUT
	 *
	 *************************/
	$(document).on('click', '.extra-page-builder .layout-selected', function () {
		var $layoutChoices = $(this).closest('.extra-page-builder-row-admin').find('.layout-choices');

		if ($layoutChoices.hasClass('open')) {
			TweenMax.to($layoutChoices, 0.3, {width: 0});
			$layoutChoices.removeClass('open');
		} else {
			TweenMax.to($layoutChoices, 0.3, {width: 180});
			$layoutChoices.addClass('open');
		}

		return false;
	});
	$(document).on('click', '.extra-page-builder .layout-choices > .layout-button', function () {
		var $this = $(this),
			layout = $this.attr('href').substr(7),
			$rowTypeInput = $this.closest('.extra-page-builder-row').find('.extra-page-builder-row-type'),
			$layoutSelected = $this.closest('.extra-page-builder-row-admin').find('.layout-selected'),
			$rowContent  = $this.closest('.extra-page-builder-row').find('.extra-page-builder-row-content');

		$layoutSelected.find('.icon-extra-page-builder').attr('class', 'icon-extra-page-builder icon-extra-page-builder-'+layout);
		$rowContent.attr('class', 'extra-page-builder-row-content extra-page-builder-row-content-'+layout);
		$rowTypeInput.val(layout);

		$layoutSelected.trigger('click');

		return false;
	});


	/**************************
	 *
	 * CHOOSE BLOCK
	 *
	 *************************/
	var $block_choose = null;
	$(document).on('click', '.extra-page-builder .choose-block .choose-link', function () {
		var $this = $(this),
			$chooseBlock = $this.closest('.choose-block'),
			$block = $this.closest('.extra-page-builder-block'),
			$choices = $chooseBlock.find('.choose-block-choices');

		$block_choose = $block;

		$last_modal_block = null;
		extraAdminModal
			.options({footer:[], size: {height: '358', width: '400'}})
			.show('Choisir un bloc', $choices.clone());

		return false;
	});

	$(document).on('click', '.choose-block-choices .choose-block-button', function () {
		if ($block_choose != null) {
			var $this = $(this),
				$block = $block_choose,
				$blockWrapper = $block.find('.extra-page-builder-block-wrapper'),
				$inputBlockChoice = $block.find('.extra-page-builder-block-choice'),
				value = $this.data('value'),
				blockId = $block.data('block-number'),
				$inputReference = $block.find('.extra-page-builder-block-choice'),
				rowId = $inputReference.attr('name').replace('[page_builder_block_choice_'+blockId+']', '');

			$inputBlockChoice.val(value);
			$block_choose.removeClass('not-selected');
			extraAdminModal.hide();

			$.get(
				ajax_url,
				{
					action: 'extra_page_builder_block',
					block_type: value,
					block_id: blockId,
					row_id: rowId
				},
				function (data) {
					$blockWrapper.html(data);

					$last_modal_block = $block;
					$('.extra-page-builder').trigger('showform.pagebuilder.extra', [value, $block, $block.find('.extra-field-form')]);
				}
			);
		}


		return false;
	});

	/**************************
	 *
	 * RESET BLOCK
	 *
	 *************************/
	$(document).on('click', '.extra-page-builder .delete-block', function () {
		var $this = $(this),
			$block = $this.closest('.extra-page-builder-block'),
			$blockContent = $block.find('.extra-page-builder-block-content'),
			$blockForm = $block.find('.extra-page-builder-block-form'),
			$inputBlockChoice = $block.find('.extra-page-builder-block-choice');

		$block.addClass('not-selected');
		$inputBlockChoice.val('');

		$blockContent.html('');
		$blockForm.html('');

		return false;
	});


	/**************************
	 *
	 * EDIT BLOCK
	 *
	 *************************/
	$(document).on('click', '.extra-page-builder .edit-block', function () {
		var $this = $(this),
			$block = $this.closest('.extra-page-builder-block'),
			block_type = $block.find('.extra-page-builder-block-choice').val();

		$last_modal_block = $block;
		$('.extra-page-builder').trigger('showform.pagebuilder.extra', [block_type, $block, $block.find('.extra-field-form')]);

		return false;
	});

	var $last_modal_block = null;
	$(document).on('showform.pagebuilder.extra', function (event, $block_type, $block, $form) {
		extraAdminModal.show('Modifier le bloc',  $form);
	});

	$(document).on('close.adminmodal.extra', function (event, $form) {
		if ($last_modal_block != null && $form != null) {
			$('.extra-page-builder').trigger('hideForm.pagebuilder.extra', [$last_modal_block.find('.extra-page-builder-block-choice').val(), $last_modal_block, $form]);
		}
	});

	$(document).on('hideForm.pagebuilder.extra', function (event, $block_type, $block, $form) {
		$last_modal_block.find('.extra-page-builder-block-form').append($form);
		$('.extra-page-builder').trigger('refreshPreview.pagebuilder.extra', [$last_modal_block.find('.extra-page-builder-block-choice').val(), $last_modal_block, $block.find('.extra-field-form')]);
	});

	$(document).on('save.adminmodal.extra', function (event, $form) {
		extraAdminModal.hide();
	});
});
