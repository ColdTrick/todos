<?php

$guid = (int) get_input('guid');

if (empty($guid)) {
	register_error(elgg_echo('InvalidParameterException:MissingParameter'));
	forward(REFERER);
}

$entity = get_entity($guid);
if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward(REFERER);
}

if (!$entity->canEdit()) {
	register_error(elgg_echo('InvalidParameterException:NoEntityFound'));
	forward(REFERER);
}

$title = $entity->title;
$entity_url = $entity->getURL();

$forward_url = REFERER;
$forward_alt = $entity->getContainerEntity()->getURL();

if ($entity->delete()) {
	system_message(elgg_echo('entity:delete:success', array($title)));
	
	if ($_SERVER['HTTP_REFERER'] === $entity_url) {
		$forward_url = $forward_alt;
	}
} else {
	register_error(elgg_echo('entity:delete:fail', array($title)));
}

forward($forward_url);