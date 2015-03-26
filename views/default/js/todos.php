<?php
?>
$(document).ready(function() {
	$(".todos-list-todolist").sortable();
	
	$(".todos-list-todoitem").sortable({
		connectWith: ".todos-list-todolist .todos-list-todoitem"
	});
	
	$(".todos-list-item .elgg-input-checkbox").change(function() {
		var guid = $(this).attr('rel');
		elgg.action('todos/todoitem/toggle', {guid: guid}, function() {
			console.log('toggle');
		});
	});
});