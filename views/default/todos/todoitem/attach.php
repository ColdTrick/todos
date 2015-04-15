<?php

$guid = (int) get_input('guid');
$entity = get_entity($guid);
if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
	echo elgg_echo("todos:todoitem:error:not_item");
	return;
}

$form_vars = array(
	'id' => 'todos-todoitem-attach',
	'enctype' => 'multipart/form-data'
);
$body_vars = array(
	'entity' => $entity
);
echo elgg_view_form('todos/todoitem/attach', $form_vars, $body_vars);
