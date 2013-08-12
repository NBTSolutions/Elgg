window.embed_text = window.embed_text || 'document';
$(document).ready(function() {
	setTimeout(function() {
		$('.elgg-menu-item-embed > a').click(); // show lightbox.
		//setTimeout(function() {
			//$('.embed-wrapper > h2').html('Choose ' + embed_text + ' to include in the discussion');
		//}, 750);
		// in progress...
	}, 750);
});

