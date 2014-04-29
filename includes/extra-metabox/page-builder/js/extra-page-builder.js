jQuery(document).ready(function($){

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
	$(document).on('click', '.extra-page-builder .choose-block .choose-link', function () {
		var $this = $(this),
			$chooseBlock = $this.closest('.choose-block'),
			$choices = $chooseBlock.find('.choose-block-choices');

		extraShowAdminModal('Choisir un bloc', $choices.clone(), {size: {height: '300', width: '400'}});

		return false;
	});

	$(document).on('click', '.extra-page-builder .choose-block .choose-block-button', function () {
		var $this = $(this),
			$block = $this.closest('.extra-page-builder-block'),
			$blockContent = $block.find('.extra-page-builder-block-content'),
			$blockForm = $block.find('.extra-page-builder-block-form'),
			$inputBlockChoice = $block.find('.extra-page-builder-block-choice'),
			$mask = $block.find('.choose-block-mask'),
			value = $this.data('value'),
			blockId = $block.data('block-number');

		$inputBlockChoice.val(value);
		$block.removeClass('not-selected');

		//TODO replace with field content front
		$blockContent.html(value);

		$.get(
			ajax_url,
			{
				action: 'extra_page_builder_block_content_form',
				block_type: value,
				block_id: blockId
			},
			function(data) {
				$blockForm.html(data);
				showForm($blockForm);
			}
		);


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
			$blockForm = $block.find('.extra-page-builder-block-form');

		showForm($blockForm);

		return false;
	});

	var $lastBlocForm = null;
	var $lastFieldForm = null;
	$(document).on('click', '.extra-admin-modal-save', function () {
		var $closeModal = $('#extra-admin-modal-container .extra-admin-modal-close');
		$closeModal.trigger('click');
	});
	$(document).on('extra-admin-modal-close', '.extra-admin-modal-close', function () {
		if ($lastBlocForm != null) {
			console.log($lastFieldForm);
			$lastBlocForm.append($lastFieldForm);
		}

		$lastBlocForm = null;
		$lastFieldForm = null;
	});
	function showForm($blockForm) {
		$lastBlocForm = $blockForm;
		$lastFieldForm = $blockForm.find('.extra-field-form');
		extraShowAdminModal('Modifier le bloc', $lastFieldForm);
	}
});
