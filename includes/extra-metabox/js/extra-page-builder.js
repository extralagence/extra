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

		console.log('delete');

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
			$div = $this.closest('div'),
			$mask = $this.closest('.choose-block-mask'),
			move = $div.outerHeight(true);

		TweenMax.to($mask, 0.3, {top: -move});

		return false;
	});

	$(document).on('click', '.extra-page-builder .choose-block .choose-block-button', function () {
		var $this = $(this),
			$block = $this.closest('.extra-page-builder-block'),
			$blockContent = $block.find('.extra-page-builder-block-content'),
			$inputBlockChoice = $block.find('.extra-page-builder-block-choice'),
			$mask = $block.find('.choose-block-mask'),
			value = $this.data('value');

		$inputBlockChoice.val(value);
		TweenMax.to($mask, 0, {top: 0});
		$block.removeClass('not-selected');

		//TODO replace with field content front
		$blockContent.html(value);

		extraPageBuilderShowEditModal(value);


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
			$inputBlockChoice = $block.find('.extra-page-builder-block-choice'),
			$templateBlank = $block.closest('.extra-page-builder').find('.extra-page-builder-template > .choose-block');

		$block.addClass('not-selected');
		$inputBlockChoice.val('');

		return false;
	});


	/**************************
	 *
	 * EDIT BLOCK
	 *
	 *************************/
	$(document).on('click', '.extra-page-builder .edit-block', function () {

		console.log('edit-block');
		//TODO get block choice
		extraPageBuilderShowEditModal('map');

		return false;
	});

	function extraPageBuilderShowEditModal(value) {
		$.get(
			ajax_url,
			{
				action: 'extra_page_builder_block_content_form',
				block_type: value
			},
			function(data) {
				// TODO show wordpress popup with form.
				extraShowAdminModal('Modifier le bloc', data);
			}
		);
	}
});
