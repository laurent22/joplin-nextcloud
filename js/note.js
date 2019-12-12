window.addEventListener('load', function() {
	var md = window.markdownit();
	md.set({
		breaks: false,
		//linkify: true,
		html: true
	});

	var bodys = document.getElementsByClassName('joplinNoteBodySource');
	for (var i = 0; i < bodys.length; i++) {
		var body = bodys[i];
		var text = body.textContent;
		body.style.display = 'none';

		var newElement = document.createElement('p');
		newElement.innerHTML = md.render(text);
		newElement.className = 'joplinNoteBody';
		body.parentNode.appendChild(newElement);		
	}    
})
