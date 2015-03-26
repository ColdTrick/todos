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


echo elgg_view_form('todos/todoitem/edit', array('id' => 'todos-todoitem-edit'), array('container_guid' => $container_guid, 'entity' => $entity));

// datepicker lightbox and userpicker fix
?>
<script>
	elgg.ui.initDatePicker();

	elgg.userpicker.init();
</script>