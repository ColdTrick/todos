<?php
?>
$(document).ready(function() {
	$(".todos-list-todolist").sortable({
		update: function( event, ui ) {

			var guid = $(ui.item).attr('id').replace('elgg-object-', '');
			var pos = $(ui.item).index();
			
			elgg.action('todos/todo/move', {
				pos: pos,
				guid: guid
			});
		}	
	});
	
	$(".todos-list-todoitem").sortable({
		connectWith: ".todos-list-todolist .todos-list-todoitem",
		update: function( event, ui ) {

			var guid = $(ui.item).attr('id').replace('elgg-object-', '');
			var pos = $(ui.item).index();
			var container_guid = $(ui.item).parent().parent().attr('id').replace('elgg-object-', '');
			
			elgg.action('todos/todo/move', {
				pos: pos,
				guid: guid,
				container_guid: container_guid
			});
		}
	});
	
	$(".todos-list-item .elgg-input-checkbox").change(function() {
		var guid = $(this).attr('rel');
		elgg.action('todos/todoitem/toggle', {guid: guid});
	});
});