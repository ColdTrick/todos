<?php

$guid = (int) get_input('guid');
$entity = get_entity($guid);
if (!$entity instanceof \TodoItem) {
	echo elgg_echo("todos:todoitem:error:not_item");
	return;
}

$form_vars = [
	'id' => 'todos-todoitem-attach',
];
$body_vars = [
	'entity' => $entity,
];
echo elgg_view_form('todos/todoitem/attach', $form_vars, $body_vars);
