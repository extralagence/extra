$(document).ready(function() {

	if(typeof(disableExtraEditorSlider) === 'undefined' || disableExtraEditorSlider === false) {
        $(".content .extra-editor-slider").extraSlider({
            paginate: true,
            navigate: true,
            resize: true,
            onInit: function(currentItem, total, slider) {
                slider.find('.cloned .responsiveImagePlaceholder').each(function() {
                    $window.trigger('extra.responsiveImage', [$(this).data("size", "")]);
                });
            }
        });
    }

});