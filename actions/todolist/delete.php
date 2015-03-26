<?php

$guid = (int) get_input('guid');

if (empty($guid)) {
	register_error(elgg_echo('InvalidParameterException:MissingParameter'));
	forward(REFERER);
}

$entity = get_entity($guid);
if (empty($entity) || !elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward(REFERER);
}

if (!$entity->canEdit()) {
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward(REFERER);
}

$title = $entity->title;

if ($entity->delete()) {
	system_message(elgg_echo('entity:delete:success', array($title)));
} else {
	register_error(elgg_echo('entity:delete:fail', array($title)));
}

forward(REFERER);