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
	'full_view' => false,
	'order_by_metadata' => [
		'name' => 'completed',
		'as' => 'integer',
		'direction' => 'desc',
	],
	'pagination' => false,
	'no_results' => elgg_echo('todos:widget:closed:none'),
];

if ($widget->getOwnerEntity() instanceof \ElggGroup) {
	$dbprefix = elgg_get_config('dbprefix');
	
	$options['joins'] = ["JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid"];
	$options['wheres'] = ["ce.container_guid = {$widget->getOwnerGUID()}"];
}

echo elgg_list_entities($options);
