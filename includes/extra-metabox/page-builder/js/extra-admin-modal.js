function extraShowAdminModal(title, $content, options) {
	var $modalContainer = jQuery('#extra-admin-modal-container'),
		$modal = $modalContainer.find('.extra-admin-modal'),
		$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
		$modalContent = $modalContainer.find('.extra-admin-modal-content');

	$modal.attr('style', '');
	if (options != null) {
		if (options.size != null) {
			if (options.size.width != null) {
				$modal.css('left', '50%');
				$modal.css('margin-left', '-'.options.size.width);
				$modal.css('width', options.size.width);
			}
			if (options.size.height != null) {
				$modal.css('top', '50%');
				$modal.css('margin-top', '-'.options.size.height);
				$modal.css('height', options.size.height);
			}
		}
	}

	$modalTitle.html(title);
	$modalContent.append($content);

	$modalContainer.show();
}

jQuery(document).ready(function($){
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

	$('body').append(html);

	$(document).on('click', '#extra-admin-modal-container .extra-admin-modal-close', function () {
		var $this = $(this),
			$modalContainer = jQuery('#extra-admin-modal-container'),
			$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
			$modalContent = $modalContainer.find('.extra-admin-modal-content');

		$modalContainer.hide();
		$modalTitle.html('');
		$modalContent.html('');

		$this.trigger('extra-admin-modal-close');
	});
});