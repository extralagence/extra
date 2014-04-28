function extraShowAdminModal(title, content) {
	var $modalContainer = jQuery('#extra-admin-modal-container'),
		$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
		$modalContent = $modalContainer.find('.extra-admin-modal-content');

	$modalTitle.html(title);
	$modalContent.html(content);

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
//		'		<div class="extra-admin-modal-footer"></div>' +
			'</div>' +
		'</div>';


	$('body').append(html);

	$(document).on('click', '#extra-admin-modal-container .extra-admin-modal-close', function () {
		var $modalContainer = jQuery('#extra-admin-modal-container'),
			$modalTitle = $modalContainer.find('.extra-admin-modal-title'),
			$modalContent = $modalContainer.find('.extra-admin-modal-content');

		$modalContainer.hide();
		$modalTitle.html('');
		$modalContent.html('');
	});
});