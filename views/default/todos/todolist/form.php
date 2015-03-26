<?php

$entity = null;
$guid = (int) get_input('guid');
if ($guid) {
	$entity = get_entity($guid);
	if (!elgg_instanceof($entity, 'object', TodoList::SUBTYPE)) {
		unset($entity);
	}
}

echo elgg_view_form('todos/todolist/edit', array('id' => 'todos-todolist-edit'), array('entity' => $entity));
