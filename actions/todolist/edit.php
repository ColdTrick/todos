<?php

$title = get_input('title');
$access_id = (int) get_input('access_id');
$guid = (int) get_input('guid');

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
} else {
	$entity = new TodoList();
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
