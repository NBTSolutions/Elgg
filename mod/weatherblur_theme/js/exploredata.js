$(document).ready(function() {
	$('.friends-picker-container input[type=checkbox]').change(function() {
		var guid = this.value;
		var checked = this.checked;
		if (graph) {
			graph.$.graphControls.userToggled({checked: checked, value: guid});
		}
	});
    // get schools too
    $('#school_list input[type=checkbox]').change(function() {
		var guid = this.value;
		var checked = this.checked;
		if (graph) {
			graph.$.graphControls.userToggled({checked: checked, value: guid});
		}
    });
});


