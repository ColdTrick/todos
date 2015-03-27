<?php

$title = get_input('title');
$access_id = (int) get_input('access_id', ACCESS_PRIVATE);
$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid');

$entity = null;

if (empty($title)) {
	register_error(elgg_echo('todos:action:error:title'));
	forward(REFERER);
}

if (!empty($guid)) {
	$entity = get_entity($guid);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
		register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
		forward(REFERER);
	}
	
	if (!$entity->canEdit()) {
		register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
		forward(REFERER);
	}
}

if (empty($entity) && can_write_to_container(0, $container_guid, 'object', TodoList::SUBTYPE)) {
	$entity = new TodoList();
	$entity->container_guid = $container_guid;
}

if (empty($entity)) {
	// this should not happen
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward(REFERER);
}

$entity->title = $title;
$entity->access_id = $access_id;
$entity->active = true;

if ($entity->save()) {
	system_message(elgg_echo('todos:action:todolist:edit:success'));
} else {
	register_error(elgg_echo('todos:action:todolist:edit:error'));
}

forward(REFERER);
