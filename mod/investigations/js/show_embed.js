$(document).ready(function() {
	console.log($('.elgg-menu-item-embed > a').click);
	$('.elgg-menu-item-embed > a').click(); // show lightbox.
	$('.embed-wrapper > h2').html('Choose <?php echo $text; ?> to include in the discussion');
});

