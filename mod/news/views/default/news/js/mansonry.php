<?php
?>
<script type="text/javascript">
$(document).ready(function(){	
	$(function() {
		if($('.news-list-news .elgg-item').length > 1) {
    		$('.news-list-news .elgg-item').css('width', '340px');
    		$('.news-list-news .elgg-item').css('margin', '10px');
    		$('.news-list-news .elgg-item').css('float', 'left');
    		$(".news-list-news").masonry({
    		    // options
    		    itemSelector : '.elgg-item',
    		    columnWidth : 360
    		});
    	}
	});
});	
</script>
<?php
