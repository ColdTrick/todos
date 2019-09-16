<?php

$entity = elgg_extract('entity', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

$title = '';
$access_id = ACCESS_DEFAULT;
$show_access = false;

if ($entity) {
	$title = $entity->title;
	
	if ($entity->getContainerEntity() instanceof \ElggGroup) {
		$show_access = true;
		$access_id = (int) $entity->access_id;
	}
	
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	]);
}

if (!$show_access && !empty($container_guid)) {
	$container = get_entity($container_guid);
	if ($container instanceof \ElggGroup) {
		$show_access = true;
	}
}

echo elgg_view_field([
	'#type' => 'hidden',
	'value' => $container_guid,
	'name' => 'container_guid',
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('todos:todolist:title'),
	'value' => $title,
	'name' => 'title',
	'required' => true,
]);

if ($show_access) {
	echo elgg_view_field([
		'#label' => elgg_echo('access'),
		'#type' => 'access',
		'name' => 'access_id',
		'value' => $access_id,
		'entity' => $entity,
		'entity_type' => 'object',
		'entity_subtype' => 'todolist',
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
