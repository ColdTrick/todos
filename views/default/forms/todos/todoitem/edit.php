<?php

$entity = elgg_extract('entity', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

$title = '';
$description = '';
$tags = '';
$assignee = null;
$due = null;

if (!empty($entity)) {
	$title = $entity->title;
	$description = $entity->description;
	$tags = $entity->tags;
	$assignee = $entity->assignee;
	$due = $entity->due;
	$container_guid = $entity->getContainerGUID();
	
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $entity->guid,
	]);
}

echo elgg_view_field([
	'#type' => 'hidden',
	'value' => $container_guid,
	'name' => 'container_guid',
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('todos:todoitem:title'),
	'value' => $title,
	'name' => 'title',
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => elgg_echo('todos:todoitem:description'),
	'value' => $description,
	'name' => 'description',
	'editor_type' => 'simple',
]);

echo elgg_view_field([
	'#type' => 'tags',
	'#label' => elgg_echo('todos:todoitem:tags'),
	'value' => $tags,
	'name' => 'tags',
]);

$list = get_entity($container_guid);
if (!empty($list) && $list->getContainerEntity() instanceof \ElggGroup) {
	echo elgg_view_field([
		'#type' => 'userpicker',
		'#label' => elgg_echo('todos:todoitem:assignee'),
		'value' => $assignee,
	]);
}

echo elgg_view_field([
	'#type' => 'date',
	'#label' => elgg_echo('todos:todoitem:due'),
	'value' => $due,
	'name' => 'due',
	'timestamp' => true,
]);

if (empty($entity)) {
	echo elgg_view_field([
		'#type' => 'file',
		'#label' => elgg_echo('todos:todoitem:attachment'),
		'name' => 'attachment',
	]);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
