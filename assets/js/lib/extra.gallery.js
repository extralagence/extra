$(document).ready(function() {

	if(typeof(disableExtraEditorSlider) === 'undefined' || disableExtraEditorSlider === false) {
        $(".content .extra-editor-slider").extraSlider({
            paginate: true,
            navigate: true
        });
    }

});