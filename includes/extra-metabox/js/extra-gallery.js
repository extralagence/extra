var originalTrigger = wp.media.view.MediaFrame.Post.prototype.trigger;
wp.media.view.MediaFrame.Post.prototype.trigger = function () {
	console.log('Event Triggered:', arguments);
	originalTrigger.apply(this, Array.prototype.slice.call(arguments));
}

jQuery(document).ready(function ($) {

	function extra_process_gallery(elmt) {

		elmt.not(".extra-gallery-processed").each(function () {

			var $element = $(this),
				$input = $element.find('.gallery-input'),
				$thumbs = $element.find('.thumbs'),
				extra_gallery_frame;

			$element.addClass("extra-gallery-processed");

			$element.on("click", ".choose-button", function (event) {

				event.preventDefault();

				if (!extra_gallery_frame) {
					extra_gallery_frame = wp.media.extra_gallery_frame = wp.media({
						id: 'extra-metabox-gallery-frame',
						frame: 'post',
						state: 'gallery-edit',
						title: wp.media.view.l10n.editGalleryTitle,
						editing: true,
						multiple: true,
						selection: getSelection()
					}).on('update close escape', function (obj) {
						var thumbs = extra_gallery_frame.states.get('gallery-edit').get('library').models;
						var numberlist = [];
						$thumbs.html('');
						if(!thumbs.length) {
							return;
						}
						$.each(thumbs, function (key, attachment) {
							numberlist.push(attachment.id);
							appendImage(attachment);
						});
						$input.val(numberlist);
						return false;
					}).on('open', function() {
						if($input.val() != '') {
							extra_gallery_frame.states.get('gallery-edit').set('library', getSelection());
							extra_gallery_frame.content.render();
						}
					});
				}
				extra_gallery_frame.open();
			});

			$element.parent().on('extra.openGallery', $.proxy(function() {
				$element.find(".choose-button").click();
				extra_gallery_frame.open();
			}, this));

			$thumbs.find(".image .close").on("click", function () {
				deleteImage($(this).parent().find("img"));
			});
			$thumbs.sortable({
				stop: updateList,
				placeholder: "extra-gallery-placeholder image",
				forcePlaceholderSize: true
			});

			function getSelection() {

				if ($input.val() == '') {
					return;
				}

				var shortcode = new wp.shortcode({
						'tag': 'gallery',
						'attrs': {
							'ids': $input.val()
						},
						'type': 'single',
						'content': null
					}),
					defaultPostId = wp.media.gallery.defaults.id,
					attachments,
					selection;

				// Bail if we didn't match the shortcode or all of the content.
				if (!shortcode)
					return;

				// Ignore the rest of the match object.
				//shortcode = shortcode.shortcode;

				if (_.isUndefined(shortcode.get('id')) && !_.isUndefined(defaultPostId))
					shortcode.set('id', defaultPostId);

				attachments = wp.media.gallery.attachments(shortcode);
				selection = new wp.media.model.Selection(attachments.models, {
					props: attachments.props.toJSON(),
					multiple: true
				});

				selection.gallery = attachments.gallery;

				// Fetch the query's attachments, and then break ties from the
				// query to allow for sorting.
				selection.more().done(function () {
					// Break ties with the query.
					selection.props.set({query: false});
					selection.unmirror();
					selection.props.unset('orderby');
				});

				return selection;
			}

			function updateList(event, ui) {
				var values = [];
				$thumbs.find(".image").each(function () {
					values.push($(this).find("img").data("id"));
				});
				$input.val(values.join(","));
			}

			function appendImage(attachment) {
				var size;
				if (attachment.attributes.sizes.thumbnail) {
					size = attachment.attributes.sizes.thumbnail;
				} else {
					size = attachment.attributes.sizes[0];
				}
				var image = $('<img data-id="' + attachment.id + '" src="' + size.url + '" width="150" />');
				image.appendTo($thumbs).wrap('<span class="image"></span>');
				image.after('<div class="close dashicons dashicons-no"></div>');
				image.parent().on("click", function () {
					deleteImage($(this).find("img"));
				});
			}

			function deleteImage(image) {
				var index = image.parent().index();
				var value = $input.val().split(",");
				value.splice(index, 1);
				image.parent().remove();
				$input.val(value.join(","));
			}


		});
	}


	$.wpalchemy.bind('wpa_copy', function (e, elmt) {
		extra_process_gallery($(elmt));
	});

	extra_process_gallery($('.extra-metabox .extra-custom-gallery'));


});

wp.media.ExtraMetaboxGallery = {

	frame: function () {
	},

	init: function () {
		$('#upload-and-attach-link').click(function (event) {
			event.preventDefault();

			wp.media.shibaMlibEditGallery.frame().open();

		});
	}
};