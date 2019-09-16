<?php

$full = (bool) elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars);

$show_completed = (bool) elgg_extract('show_completed', $vars, false);
$list_completed = (bool) elgg_extract('list_completed', $vars, true); // only applies to full view

if (!$entity instanceof \TodoList) {
	return;
}

$content = '';

$options = [
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => false,
	'full_view' => false,
	'pagination' => false,
	'item_class' => 'todos-list-item',
	'list_class' => [
		'todos-list',
		'todos-list-todoitem',
		"elgg-todo-{$entity->guid}",
	],
	'container_guid' => $entity->guid,
];

if (!$show_completed) {
	$options['order_by_metadata'] = [
		'name' => 'order',
		'as' => 'integer',
	];
}

if ($entity->canWriteToContainer()) {
	$options['list_class'][] = 'todos-sortable';
}

$active_todos = elgg_list_entities($options);

$content .= $active_todos;

if ($entity->canWriteToContainer(0, 'object', TodoItem::SUBTYPE)) {
	if (empty($active_todos) && !$full) {
		// add an empty place to drop todos from other lists
		$content .= "<ul class='elgg-list todos-list todos-list-todoitem todos-sortable elgg-todo-{$entity->guid}'></ul>";
	}
	
	$content .= '<div>';
	$content .= elgg_view('output/url', [
		'text' => elgg_echo('todos:todoitem:add'),
		'icon' => 'plus',
		'class' => 'elgg-lightbox mll',
		'href' => 'ajax/view/todos/todoitem/form?container_guid=' . $entity->guid,
	]);
	$content .= '</div>';
}

if ($full && $list_completed) {
	// list completed todos
	$completed_list = elgg_list_entities([
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'limit' => false,
		'full_view' => false,
		'pagination' => false,
		'item_class' => 'todos-list-item',
		'list_class' => 'todos-list',
		'container_guid' => $entity->guid,
		'order_by_metadata' => [
			'name' => 'completed',
			'as' => 'integer',
			'direction' => 'DESC',
		],
	]);
	
	if ($completed_list) {
		$content .= elgg_view_module('info', elgg_echo("todos:todolist:completed"), $completed_list, ['class' => 'mtl']);
	}
}

if (elgg_extract('full_view', $vars)) {
	$params = [
		'icon' => false,
		'body' => $content,
		'show_summary' => true,
		'show_social_menu' => false,
		'show_navigation' => false,
	];
	$params = $params + $vars;
	
	echo elgg_view('object/elements/full', $params);
} else {
	// brief view
	$params = [
		'content' => $content,
		'show_social_menu' => false,
		'subtitle' => false,
		'icon' => false,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
