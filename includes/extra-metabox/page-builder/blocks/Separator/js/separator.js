jQuery(document).ready(function ($) {
	$('.extra-page-builder').on('showform.pagebuilder.extra', function (event, $block_type, $block) {
		if ($block_type == 'separator') {
			// We stop propagation to change default behavior
			event.stopPropagation();
		}
	});
});