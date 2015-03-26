<?php

gatekeeper();

$page_owner = elgg_get_page_owner_entity();
if (empty($page_owner) || !elgg_instanceof($page_owner, 'user')) {
	$page_owner = elgg_get_logged_in_user_entity();
}

if (!$page_owner->canEdit()) {
	forward(REFERER);
}

elgg_set_page_owner_guid($page_owner->getGUID());

elgg_push_breadcrumb(elgg_echo('todos'), 'todos');
elgg_push_breadcrumb($page_owner->name);

$title = elgg_echo("todos:filter:assigned");

// open assigned todo items
$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => false,
	'metadata_name_value_pairs' => array(
		array(
			'name' => 'assignee',
			'value' => $page_owner->getGUID()
		),
		array(
			'name' => 'order',
			'value' => 0,
			'operand' => '>'
		)
	),
	'full_view' => false,
	'list_class' => 'todos-list'
);
$content = elgg_list_entities_from_metadata($options);
if (empty($content)) {
	$content = elgg_echo('todos:assigned:no_results');
}

// closed assigned todo items
$options['limit'] = 10;
$options['metadata_name_value_pairs'] = array(
	array(
		'name' => 'assignee',
		'value' => $page_owner->getGUID()
	),
	array(
		'name' => 'completed',
		'value' => 0,
		'operand' => '>'
	)
);
$options['order_by_metadata'] = array(
	'name' => 'completed',
	'as' => 'integer',
	'direction' => 'desc'
);
$options['pagination'] = false;
$options['item_class'] = 'todos-list-item-completed';
$options['list_class'] = 'todos-list';

$closed = elgg_list_entities_from_metadata($options);
if (!empty($closed)) {
	$content .= elgg_view_module('info', elgg_echo('todos:assigned:closed'), $closed, array('class' => 'mtl'));
}

$filter = elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));

$body = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $filter,
	'content' => $content,
	'sidebar' => elgg_view('todos/sidebar')
));

echo elgg_view_page($title, $body);
