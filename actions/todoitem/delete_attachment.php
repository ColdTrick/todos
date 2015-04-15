<?php

$guid = (int) get_input('guid');
$filename = get_input('filename');

if (empty($guid) || empty($filename)) {
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

if ($entity->deleteAttachment($filename)) {
	system_message(elgg_echo('todos:action:todoitem:delete_attachment:success'));
} else {
	register_error(elgg_echo('todos:action:todoitem:delete_attachment:error'));
}

forward(REFERER);