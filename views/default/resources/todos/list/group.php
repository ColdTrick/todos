<?php

elgg_gatekeeper();

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

if ($page_owner instanceof \ElggUser) {
	elgg_push_breadcrumb(elgg_echo('todos'), "todos/group/{$page_owner->guid}/all");
}

$options = array(
	'type' => 'object',
	'subtype' => 'todolist',
	'container_guid' => $container_guid,
	'limit' => false,
	'full_view' => false,
	'pagination' => false,
	'list_class' => 'todos-list todos-list-todolist',
	'order_by_metadata' => array(
		'name' => 'order',
		'as' => 'integer'
	),
);

if ($page_owner->canWriteToContainer()) {
	$options['list_class'] .= ' todos-sortable';
}

switch ($filter) {
	case 'active':
		
		if ($page_owner->canWriteToContainer(0, 'object', TodoList::SUBTYPE)) {
			
			$item = ElggMenuItem::factory([
				'text' => elgg_echo('todos:todolist:add'),
				'href' => "ajax/view/todos/todolist/form?container_guid={$page_owner->guid}",
				'name' => 'todolist_add',
				'link_class' => 'elgg-button elgg-button-action elgg-lightbox'
			]);
			elgg_register_menu_item('title', $item);
		}
		
		$options['metadata_name_value_pairs'] = array('active' => true);
		break;
	case 'completed':
		$options['metadata_name_value_pairs'] = array('active' => false);
		$options['show_completed'] = true;
		break;
	case 'overdue':
		$dbprefix = elgg_get_config('dbprefix');
		
		$options = array(
			'type' => 'object',
			'subtype' => TodoItem::SUBTYPE,
			'limit' => false,
			'full_view' => false,
			'metadata_name_value_pairs' => array(
				array(
					'name' => 'order',
					'value' => 0,
					'operand' => '>',
				),
				array(
					'name' => 'due',
					'value' => time(),
					'operand' => '<',
				),
			),
			'order_by_metadata' => array(
				'name' => 'due',
				'as' => 'integer',
				'order' => 'asc',
			),
			'joins' => array(
				"JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid",
			),
			'wheres' => array(
				"ce.container_guid = {$container_guid}",
			),
		);
		break;
}

$title = elgg_echo("todos:filter:$filter");

$content = elgg_list_entities($options);
if (empty($content)) {
	$content = elgg_echo('todos:all:no_results');
}

$filter = elgg_view_menu('filter', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz'
));

$body = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $filter,
	'content' => $content,
	'sidebar' => elgg_view('todos/sidebar')
));

echo elgg_view_page($title, $body);
