<?php

$guid = (int) get_input('guid');
$filename = get_input('filename');

if (empty($guid) || empty($filename)) {
	return elgg_error_response(elgg_echo('InvalidParameterException:MissingParameter'));
}

$entity = get_entity($guid);
if (!$entity instanceof \TodoItem) {
	return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
}

if (!$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

if (!$entity->deleteAttachment($filename)) {
	return elgg_error_response(elgg_echo('todos:action:todoitem:delete_attachment:error'));
}

return elgg_ok_response('', elgg_echo('todos:action:todoitem:delete_attachment:success'));
