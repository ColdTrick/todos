<?php

$title = get_input('title');
$container_guid = get_input('container_guid');

if (empty($title)) {
	register_error(elgg_echo('missing_title'));
	forward(REFERER);
}

if (empty($container_guid)) {
	register_error(elgg_echo('missing_container'));
	forward(REFERER);
}

$todolist = get_entity($container_guid);
if (!elgg_instanceof($todolist, 'object', TodoList::SUBTYPE)) {
	register_error(elgg_echo('missing_container'));
	forward(REFERER);
}

$entity = new TodoItem();
$entity->title = $title;
$entity->active = true;
$entity->container_guid = $todolist->guid;
$entity->access_id = $todolist->access_id;

if ($entity->save()) {
	system_message(elgg_echo('saved'));
} else {
	register_error(elgg_echo('save failed'));
}

forward(REFERER);
