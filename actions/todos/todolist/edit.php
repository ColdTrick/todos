<?php

$title = get_input('title');
$access_id = (int) get_input('access_id', ACCESS_PRIVATE);
$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid');

$entity = null;

if (empty($title)) {
	return elgg_error_response(elgg_echo('todos:action:error:title'));
}

if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!$entity instanceof \TodoList) {
		return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
	}
	
	if (!$entity->canEdit()) {
		return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
	}
}
$container = get_entity($container_guid);
if (empty($entity) && $container->canWriteToContainer(0, 'object', TodoList::SUBTYPE)) {
	$entity = new TodoList();
	$entity->container_guid = $container_guid;
}

if (empty($entity)) {
	// this should not happen
	return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
}

$entity->title = $title;
$entity->access_id = $access_id;
$entity->active = true;

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('todos:action:todolist:edit:error'));
}

return elgg_ok_response('', elgg_echo('todos:action:todolist:edit:success'));
