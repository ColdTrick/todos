<?php

$title = get_input('title');
$container_guid = (int) get_input('container_guid');
$guid = (int) get_input('guid');
$due = (int) get_input('due');
$assignee = get_input('members');

$entity = false;

if (empty($title)) {
	register_error(elgg_echo('todos:action:error:title'));
	forward(REFERER);
}

if (!empty($guid)) {
	$entity = get_entity($guid);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
		register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
		forward(REFERER);
	}
	
	if (!$entity->canEdit()) {
		register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
		forward(REFERER);
	}
	
	$container_guid = $entity->getContainerGUID();
}

if (!empty($assignee) && !is_array($assignee)) {
	$assignee = array($assignee);
}
if (!empty($assignee) && count($assignee) > 1) {
	register_error(elgg_echo("todos:action:todoitem:edit:assignee"));
	forward(REFERER);
}

if (empty($container_guid)) {
	register_error(elgg_echo('todos:todoitem:error:missing_container'));
	forward(REFERER);
}

$todolist = get_entity($container_guid);
if (empty($todolist) || !elgg_instanceof($todolist, 'object', TodoList::SUBTYPE)) {
	register_error(elgg_echo('todos:todoitem:error:missing_container'));
	forward(REFERER);
}

if (!$todolist->canWriteToContainer(0, 'object', TodoItem::SUBTYPE)) {
	register_error(elgg_echo('todos:action:todoitem:edit:cant_write'));
	forward(REFERER);
}

if (empty($due)) {
	unset($due);
}

$new_entity_created = false;
if (empty($entity)) {
	$entity = new TodoItem();
	$entity->container_guid = $todolist->getGUID();
	$entity->access_id = $todolist->access_id;
	
	$new_entity_created = true;
}

$entity->title = $title;
$entity->setDueDate($due);

$entity->assign($assignee);

if ($entity->save()) {
	system_message(elgg_echo('todos:action:todoitem:edit:success'));
	
	if ($new_entity_created) {
		add_to_river('river/object/todoitem/create', 'create', elgg_get_logged_in_user_guid(), $entity->guid);
	}
} else {
	register_error(elgg_echo('todos:action:todoitem:edit:error'));
}

forward(REFERER);
