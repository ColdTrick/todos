<?php

$guid = (int) get_input('guid');

$entity = get_entity($guid);
if (empty($entity) || (!elgg_instanceof($entity, 'object', TodoList::SUBTYPE) && !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE))) {
	forward();
}

$items = array();
$container = $entity->getContainerEntity();
$todolist = false;
if (elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
	
	$items = todos_todolist_menu_register('', '', array(), array('entity' => $entity));
} else {
	$todolist = $entity->getContainerEntity();
	if (!empty($todolist)) {
		$container = $todolist->getContainerEntity();
	}
	$items = todos_todoitem_menu_register('', '', array(), array('entity' => $entity, 'full_view' => true));
}

if (elgg_instanceof($container, 'group')) {
	elgg_set_page_owner_guid($container->guid);
	elgg_push_breadcrumb(elgg_echo('todos'), "todos/group/{$container->getGUID()}/all");
} else {
	elgg_push_breadcrumb(elgg_echo('todos'), 'todos');
}

if (!empty($todolist)) {
	elgg_push_breadcrumb($todolist->title, $todolist->getURL());
}

elgg_push_breadcrumb($entity->title);
	
foreach ($items as $menu_item) {
	$menu_item->setLinkClass('elgg-button elgg-button-action');
	elgg_register_menu_item('title', $menu_item);
}

$title = $entity->title;

$content = elgg_view_entity($entity, array('full_view' => true));

$body = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => false,
	'content' => $content
));

echo elgg_view_page($title, $body);