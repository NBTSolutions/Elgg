$(document).ready(function() {
	var display = $('#graph_graphControls_fromDisplay');
	$('#use-mine').click(function() {
	console.log(display);
		$('#graph_graphControls_fromDisplay').html('Me');
		$('#graph_graphControls_fromDisplay').attr('value', uid); // uid set by page
		$('#graph_people').hide();
	});

	$('#use-user').click(function() {
		var val = $('input[name="user_guid[]"]:checked', '.friends-picker-container').val();
		if (val) {
			var selected_name = $('input[name="user_guid[]"]:checked', '.friends-picker-container').parent().siblings().last().html().trim();
			$('#graph_graphControls_fromDisplay').html(selected_name);
			$('#graph_graphControls_fromDisplay').attr('value', val);
		}
		$('#graph_people').hide();
	});

});


