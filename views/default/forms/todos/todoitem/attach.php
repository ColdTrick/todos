<?php

$entity = elgg_extract('entity', $vars);

$content = elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

$content .= elgg_view_field([
	'#type' => 'file',
	'#label' => elgg_echo('todos:todoitem:attachment'),
	'name' => 'attachment',
]);

$content .= elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('upload'),
]);

echo elgg_view_module('info', elgg_echo('todos:todoitem:attach', [$entity->getDisplayName()]), $content);
