/***************************
 *
 *
 * FILE MANAGER
 *
 *
*****************************/

jQuery(document).ready(function($) {

	function extra_process_files(elmt){

		if(elmt === undefined) {
			elmt = $('.extra-custom-file:not(".extra-custom-file-processed")');
		}

		if(!elmt.hasClass('extra-custom-file')) {
			elmt = elmt.find('.extra-custom-file');
		}

		elmt.each(function(){

			if($(this).closest('.wpa_group.tocopy').length) {
				return;
			}

			var $element	 = $(this),
				$input 		 = $element.find('.file-input'),
				title        = $element.find("label:first").text(),
				$fileName	 = $element.find('.file-name'),
				file_frame;

			$element.addClass("extra-custom-file-processed");

			$element.on("click", ".choose-button", function(event) {
				event.preventDefault();

				if ( file_frame ) {
					file_frame.open();
					return;
				}

				console.log(wp.media);

				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Sélectionner un fichier',
					button: {
						text: 'Ajouter le fichier',
					},
					multiple: false
				});

			    file_frame.on( 'select', function() {
			      attachment = file_frame.state().get('selection').first().toJSON();
			      $input.val(attachment.url);
			      $fileName.html(basename(attachment.url));
			    });
			    file_frame.open();

			});


		});
	}

	if ($.wpalchemy !== undefined) {
		$.wpalchemy.bind('wpa_copy', function(e, elmt){
			extra_process_files($(elmt));
		});
	}

	function basename(path, suffix) {
		  var b = path;
		  var lastChar = b.charAt(b.length - 1);

		  if (lastChar === '/' || lastChar === '\\') {
		    b = b.slice(0, -1);
		  }

		  b = b.replace(/^.*[\/\\]/g, '');

		  if (typeof suffix === 'string' && b.substr(b.length - suffix.length) == suffix) {
		    b = b.substr(0, b.length - suffix.length);
		  }

		  return b;
	}

	extra_process_files();
});