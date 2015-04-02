<?php
?>
$(document).ready(function() {
	$(".todos-list-todolist.todos-sortable").sortable({
		update: function(event, ui) {

			var guid = $(ui.item).attr('id').replace('elgg-object-', '');
			var pos = $(ui.item).index();
			
			elgg.action('todos/todo/move', {
				pos: pos,
				guid: guid
			});
		}	
	});
	
	$(".todos-list-todoitem.todos-sortable").sortable({
		connectWith: ".todos-list.todos-list-todoitem",
		forcePlaceholderSizeType: true,
		update: function(event, ui) {

			var guid = $(ui.item).attr('id').replace('elgg-object-', '');
			var pos = $(ui.item).index();
			var container_guid;
			
			var classes = $(ui.item).parent().attr('class').split(/\s+/);
			$.each(classes, function(index, item) {
			    if (item.indexOf('elgg-todo-') === 0) {
			       //do something
			       container_guid = item.replace('elgg-todo-', '');
			    }
			});

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