<?php

$container_guid = 0;

$guid = (int) get_input('guid');
$entity = null;
if (!empty($guid)) {
	$entity = get_entity($guid);
	if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
		unset($entity);
	} else {
		$container_guid = $entity->getContainerGUID();
	}
}

$container_guid = (int) get_input('container_guid', $container_guid);
if (empty($container_guid)) {
	echo elgg_echo("todos:todoitem:error:missing_container");
	return;
}

$form_vars = array(
	'id' => 'todos-todoitem-edit',
	'enctype' => 'multipart/form-data'
);
$body_vars = array(
	'container_guid' => $container_guid,
	'entity' => $entity
);
echo elgg_view_form('todos/todoitem/edit', $form_vars, $body_vars);

// datepicker lightbox and userpicker fix
?>
<script>
	elgg.ui.initDatePicker();

	elgg.userpicker.init();
</script>