<?php

$filter = get_input('filter', 'active');
if (!in_array($filter, ['active', 'completed', 'overdue'])) {
	$filter = 'active';
}

$page_owner = elgg_get_page_owner_entity();
if (empty($page_owner)) {
	$page_owner = elgg_get_logged_in_user_entity();
}

$container_guid = $page_owner->guid;
elgg_set_page_owner_guid($container_guid);

if (!todos_enabled_for_container($page_owner)) {
	forward(REFERER);
}

elgg_push_collection_breadcrumbs('object', 'todolist');

$options = [
	'type' => 'object',
	'subtype' => 'todolist',
	'container_guid' => $container_guid,
	'limit' => false,
	'full_view' => false,
	'pagination' => false,
	'list_class' => ['todos-list', 'todos-list-todolist'],
	'order_by_metadata' => [
		'name' => 'order',
		'as' => 'integer',
	],
	'no_results' => true,
];

if ($page_owner->canWriteToContainer()) {
	$options['list_class'][] = 'todos-sortable';
}

$filter_value = $filter;

switch ($filter) {
	case 'active':
		
		if ($page_owner->canWriteToContainer(0, 'object', TodoList::SUBTYPE)) {
			
			$item = \ElggMenuItem::factory([
				'text' => elgg_echo('todos:todolist:add'),
				'icon' => 'plus',
				'href' => "ajax/view/todos/todolist/form?container_guid={$page_owner->guid}",
				'name' => 'todolist_add',
				'link_class' => 'elgg-button elgg-button-action elgg-lightbox'
			]);
			elgg_register_menu_item('title', $item);
		}
		
		$options['metadata_name_value_pairs'] = array('active' => true);
		$options['no_results'] = elgg_echo('todos:all:no_results');
		break;
	case 'completed':
		$options['metadata_name_value_pairs'] = array('active' => false);
		$options['show_completed'] = true;
		break;
	case 'overdue':
		$dbprefix = elgg_get_config('dbprefix');
		
		$options = [
			'type' => 'object',
			'subtype' => TodoItem::SUBTYPE,
			'limit' => false,
			'full_view' => false,
			'no_results' => true,
			'metadata_name_value_pairs' => [
				[
					'name' => 'order',
					'value' => 0,
					'operand' => '>',
				],
				[
					'name' => 'due',
					'value' => time(),
					'operand' => '<',
				],
			],
			'order_by_metadata' => [
				'name' => 'due',
				'as' => 'integer',
				'order' => 'asc',
			],
			'joins' => [
				"JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid",
			],
			'wheres' => [
				"ce.container_guid = {$container_guid}",
			],
		];
		break;
}

$title = elgg_echo("todos:filter:$filter");

$content = elgg_list_entities($options);

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'todos',
	'filter_value' => $filter_value,
]);

echo elgg_view_page($title, $body);
