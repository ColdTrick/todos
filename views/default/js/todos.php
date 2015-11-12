<?php
?>
//<script>
elgg.provide('elgg.todos');

elgg.todos.init_todolist_sortable = function() {

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
};

elgg.todos.init_todoitems_sortable = function() {

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
};

elgg.todos.range_change = function() {

	var $range = $('.elgg-form-todos-filters .todos-form-filters-range');
	$range.hide();

	if ($(this).val() === 'range') {
		$range.show();
	}
};

elgg.todos.init = function() {

	elgg.todos.init_todolist_sortable();
	elgg.todos.init_todoitems_sortable();
	
	$(".todos-list-item .elgg-input-checkbox").change(function() {
		var guid = $(this).attr('rel');
		elgg.action('todos/todoitem/toggle', {guid: guid});
	});

	$('#todos-filters-date').change(elgg.todos.range_change);
};

elgg.register_hook_handler("init", "system", elgg.todos.init);
