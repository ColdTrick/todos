<?php

$guid = (int) get_input('guid');

$attachment = elgg_get_uploaded_file('attachment');
if (empty($attachment)) {
	return elgg_error_response(elgg_echo('todos:action:todoitem:attachment:error:file'));
}

$entity = get_entity($guid);
if (!$entity instanceof \TodoItem) {
	return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
}

if (!$entity->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$entity->attach($attachment->getClientOriginalName(), file_get_contents($attachment->getPathname()));

return elgg_ok_response('', elgg_echo('todos:action:todoitem:attachment:success'));
