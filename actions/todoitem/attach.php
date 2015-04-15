<?php

$guid = (int) get_input('guid');
$attachment = get_uploaded_file('attachment');

if (empty($attachment)) {
	register_error(elgg_echo('todos:action:todoitem:attachment:error:file'));
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

$filename = $_FILES['attachment']['name'];
$entity->attach($filename, $attachment);

system_message(elgg_echo('todos:action:todoitem:attachment:success'));
forward(REFERER);
