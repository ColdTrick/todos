<?php

$container_guid = 0;

$entity = null;
$guid = (int) get_input('guid');
if ($guid) {
	$entity = get_entity($guid);
	if (!elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
		unset($entity);
	} else {
		$container_guid = $entity->getContainerGUID();
	}
}

$container_guid = (int) get_input('container_guid', $container_guid);
if (empty($container_guid)) {
	echo elgg_echo("todos:todolist:error:missing_container");
	return;
}

elgg_set_page_owner_guid($container_guid);

echo elgg_view_form('todos/todolist/edit', array('id' => 'todos-todolist-edit'), array('entity' => $entity));
