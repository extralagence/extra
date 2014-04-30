function ExtraAdminModal() {
	jQuery(document).on('click', '#extra-admin-modal-container .extra-admin-modal-close', function () {
		var $modalContainer = jQuery('#extra-admin-modal-container'),
			$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
			$modalContent = $modalContainer.find('.extra-admin-modal-content'),
			$content = $modalContent.children();

		$modalContainer.trigger('close.adminmodal.extra', [$content]);

		$modalContainer.hide();
		$modalTitle.html('');
		$modalContent.html('');
	});

	jQuery(document).on('click', '#extra-admin-modal-container .extra-admin-modal-save', function () {
		var $modalContainer = jQuery('#extra-admin-modal-container'),
			$modalContent = $modalContainer.find('.extra-admin-modal-content');

		$modalContainer.trigger('save.adminmodal.extra', $modalContent.children());
	});
}

ExtraAdminModal.prototype.init = function () {
	var html =
		'<div id="extra-admin-modal-container">' +
			'	<div class="extra-admin-modal">' +
			'		<div class="extra-admin-modal-header">' +
			'			<h1 class="extra-admin-modal-title"></h1>' +
			'			<a href="#" class="extra-admin-modal-close">' +
			'				<span class="extra-admin-modal-close-icon"></span>' +
			'			</a>' +
			'		</div>' +
			'		<div class="extra-admin-modal-content"></div>' +
			'		<div class="extra-admin-modal-footer">' +
			'			<a href="#" class="extra-admin-modal-save button button-primary right">Enregistrer</a>' +
			'		</div>' +
			'	</div>' +
			'</div>';

	jQuery('body').append(html);
};

ExtraAdminModal.prototype.show = function (title, $content, options) {
	var $modalContainer = jQuery('#extra-admin-modal-container'),
		$modal = $modalContainer.find('.extra-admin-modal'),
		$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
		$modalContent = $modalContainer.find('.extra-admin-modal-content'),
		$modalFooter = $modalContainer.find('.extra-admin-modal-footer'),
		$modalFooterButtons = $modalFooter.find('> a');

	$modal.attr('style', '');
	$modalFooterButtons.attr('style', '');
	$modalFooter.attr('style', '');
	if (options != null) {
		if (options.size != null) {
			if (options.size.width != null) {
				$modal.css('margin-left', 'auto');
				$modal.css('margin-right', 'auto');
				$modal.css('width', options.size.width);
			}
			if (options.size.height != null) {
				$modal.css('margin-top', 'auto');
				$modal.css('margin-bottom', 'auto');
				$modal.css('height', options.size.height);
			}
		}

		if (options.footer) {
			$modalFooterButtons.css('display', 'none');
			if (options.footer.length > 0) {
				options.footer.forEach(function (element, index, array) {
					$modalFooter.find('> a.'+element).css('display', 'block');
				});
			} else {
				$modalFooter.css('display', 'none');
			}
		}
	}

	$modalTitle.html(title);
	$modalContent.append($content);

	$modalContainer.show();
	$modalContainer.trigger('open.adminmodal.extra', [title, $content, options]);
};

ExtraAdminModal.prototype.hide = function () {
	var $modalContainer = jQuery('#extra-admin-modal-container'),
		$modal = $modalContainer.find('.extra-admin-modal'),
		$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
		$modalContent = $modalContainer.find('.extra-admin-modal-content'),
		$modalFooter = $modalContainer.find('.extra-admin-modal-footer'),
		$modalFooterButtons = $modalFooter.find('> a'),
		$content = $modalContent.children().first();

	$modalContainer.trigger('close.adminmodal.extra', [$content]);

	$modal.attr('style', '');
	$modalFooterButtons.attr('style', '');
	$modalFooter.attr('style', '');

	$modalTitle.html('');
	$modalContent.html('');

	$modalContainer.hide();

};