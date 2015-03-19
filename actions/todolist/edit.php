<?php

$title = get_input('title');

if (empty($title)) {
	system_message(elgg_echo('missing_title'));
	forward(REFERER);
}

$entity = new TodoList();
$entity->title = $title;
$entity->active = true;

if ($entity->save()) {
	system_message(elgg_echo('saved'));
} else {
	register_error(elgg_echo('save failed'));
}

forward(REFERER);
