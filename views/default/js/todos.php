<?php
?>
$(document).ready(function() {
	$(".todos-list-todolist").sortable();
	
	$(".todos-list-todoitem").sortable({
		connectWith: ".todos-list-todolist .todos-list-todoitem"
	});
});