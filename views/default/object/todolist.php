<?php

$full = (bool) elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars);

$show_completed = (bool) elgg_extract('show_completed', $vars, false);

if (empty($entity) || !elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
	return;
}

if (!$full) {
	echo '<div class="todos-list-item">';
	echo '<h3>' . elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL(),
		'is_trusted' => true
	)) . '</h3>';
	
	echo elgg_view_menu('todolist', array(
		'entity' => $entity,
		'class' => 'elgg-menu-hz elgg-menu-todos',
		'sort_by' => 'register'
	));
	echo '</div>';
}

$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => false,
	'full_view' => false,
	'pagination' => false,
	'item_class' => 'todos-list-item',
	'list_class' => 'todos-list todos-list-todoitem elgg-todo-' . $entity->guid,
	'container_guid' => $entity->getGUID()
);

if (!$show_completed) {
	$options['order_by_metadata'] = array(
		'name' => 'order',
		'as' => 'integer'
	);
}

if (can_write_to_container(null, $entity->getContainerGUID())) {
	$options['list_class'] .= ' todos-sortable';
}

$active_todos = elgg_list_entities_from_metadata($options);
echo $active_todos;

if ($entity->canWriteToContainer(0, 'object', TodoItem::SUBTYPE)) {
	elgg_load_js("lightbox");
	elgg_load_css("lightbox");
	
	elgg_load_js('elgg.userpicker');
	elgg_load_js('jquery.ui.autocomplete.html');
	
	if (empty($active_todos) && !$full) {
		// add an empty place to drop todos from other lists
		echo "<ul class='elgg-list todos-list todos-list-todoitem todos-sortable elgg-todo-{$entity->guid}'></ul>";
	}
	
	echo '<div>';
	echo elgg_view('output/url', array(
		'text' => elgg_echo('todos:todoitem:add'),
		'class' => 'elgg-lightbox mll',
		'href' => 'ajax/view/todos/todoitem/form?container_guid=' . $entity->getGUID()
	));
	echo '</div>';
}

if ($full) {
	// list completed todos
	$options = array(
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'limit' => false,
		'full_view' => false,
		'pagination' => false,
		'item_class' => 'todos-list-item',
		'list_class' => 'todos-list',
		'container_guid' => $entity->getGUID(),
		'order_by_metadata' => array(
			'name' => 'completed',
			'as' => 'integer'
		),
	);
	
	$completed_list = elgg_list_entities_from_metadata($options);
	
	if ($completed_list) {
		echo elgg_view_module('info', elgg_echo("todos:todolist:completed"), $completed_list, array('class' => 'mtl'));
	}
}
