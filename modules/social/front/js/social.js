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

	jQuery.parseJSON(shareApis).forEach(function (share) {
		add(share['url'], share['id']);
	});

	fjs.parentNode.insertBefore(frag, fjs);
}(document, 'script'));