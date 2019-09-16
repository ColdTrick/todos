<?php

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

$options = [
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $num_display,
	'pagination' => false,
	'no_results' => elgg_echo('todos:widget:created:none'),
];

if ($widget->getOwnerEntity() instanceof \ElggGroup) {
	$dbprefix = elgg_get_config('dbprefix');
	
	$options['joins'] = ["JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid"];
	$options['wheres'] = ["ce.container_guid = {$widget->getOwnerGUID()}"];
}

echo elgg_list_entities($options);
