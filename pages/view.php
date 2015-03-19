<?php

$guid = get_input('guid');
$entity = get_entity($guid);
if (!$entity) {
	forward();
}

elgg_push_breadcrumb(elgg_echo('todos'), 'todos');

$items = array();
if ($entity instanceof TodoList) {
	$items = todos_todolist_menu_register('','', [], ['entity' => $entity]);
} else {
	$todolist = $entity->getContainerEntity();
	if ($todolist) {
		elgg_push_breadcrumb($todolist->title, $todolist->getURL());
	}
	$items = todos_todoitem_menu_register('','', [], ['entity' => $entity]);
}
	
foreach ($items as $menu_item) {
	$menu_item->setLinkClass('elgg-button elgg-button-action');
	elgg_register_menu_item('title', $menu_item);
}

$title = $entity->title;

$content = elgg_view_entity($entity, array("full_view" => true));

$body = elgg_view_layout('content', array('title' => $title, 'filter' => false, 'content' => $content));

echo elgg_view_page($title, $body);