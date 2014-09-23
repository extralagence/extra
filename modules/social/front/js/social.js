(function(doc, script) {
	var js,
		fjs = doc.getElementsByTagName(script)[0],
		frag = doc.createDocumentFragment(),
		add = function(url, id) {
			if (doc.getElementById(id)) {return;}
			js = doc.createElement(script);
			js.src = url;
			id && (js.id = id);
			frag.appendChild( js );
		};

	if (typeof shareApis !== 'undefined' && shareApis != undefined && shareApis != 'undefined' && shareApis != null) {
		var apis = $.parseJSON(shareApis);
		$.each(apis, function (index, share) {
			add(share['url'], share['id']);
		});
	}


	fjs.parentNode.insertBefore(frag, fjs);
}(document, 'script'));