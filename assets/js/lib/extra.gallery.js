$(document).ready(function() {

	if(typeof allowExtraEditorSlider != 'undefined' && allowExtraEditorSlider == true) {
		$(".content .extra-editor-slider").extraSlider({
			draggable: false,
			navigate: true,
			speed: 0.3
		});
	}

});