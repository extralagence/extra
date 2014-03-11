var _url;
var DOM = tinymce.DOM;
(function() {
	tinymce.create('tinymce.plugins.Extra', {
		init: function(ed, url) {
		
			_url = url;	
			
			var t = this,
				columnHTML; 
			
			columnHTML = '<hr class="cleaner" />';

			// Register commands
			ed.addCommand('Extra_Cleaner', function() {
				ed.execCommand('mceInsertContent', 0, columnHTML);
			});

			// Register buttons
			ed.addButton('extra_cleaner', {
				title : 'Séparateur',
				image : _url+'/../img/extra-cleaner-button.png',
				cmd : 'Extra_Cleaner'
			});
			
		}
	});	
    tinymce.PluginManager.add('extra', tinymce.plugins.Extra);
})();