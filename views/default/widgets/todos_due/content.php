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
	'metadata_name' => 'order',
	'order_by_metadata' => [
		'name' => 'due',
		'as' => 'integer',
		'direction' => 'asc',
	],
	'pagination' => false,
	'show_assignee' => true,
	'show_due' => true,
	'no_results' => elgg_echo('todos:widget:due:none'),
];

if ($widget->getContainerEntity() instanceof ElggGroup) {
	
	if ($widget->list_guid) {
		// show from one list
		$options['container_guid'] = (int) $widget->list_guid;
	} else {
		// show from all lists
		$dbprefix = elgg_get_config('dbprefix');
		
		$options['joins'] = ["JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid"];
		$options['wheres'] = ["ce.container_guid = {$widget->getOwnerGUID()}"];
	}
}

echo elgg_list_entities($options);
