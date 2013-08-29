$(document).ready(function() {
	var display = $('#graph_graphControls_fromDisplay');
	$('#use-mine, #use-any').click(function() {
		var which = this.id == 'use-mine' ? 'Me' : 'Anyone';
		var guid = this.id == 'use-mine' ? window.uid : false;
		$('#graph_graphControls_fromDisplay').html(which);
		$('#graph_graphControls_fromDisplay').attr('value', guid); // uid set by page
		$('#graph_people').hide();
	});

	$('#use-user').click(function() {
		var val = $('input[name="user_guid[]"]:checked', '.friends-picker-container').val();
		if (val) {
			var selected_name = $('input[name="user_guid[]"]:checked', '.friends-picker-container').parent().siblings('td[guid='+val+']').last().html().trim();
			$('#graph_graphControls_fromDisplay').html(selected_name);
			$('#graph_graphControls_fromDisplay').attr('value', val);
		}
		$('#graph_people').hide();
	});

});


