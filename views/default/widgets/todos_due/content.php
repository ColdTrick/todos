<?php

$widget = elgg_extract('entity', $vars);
$container = $widget->getContainerEntity();

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $num_display,
	'full_view' => false,
	'metadata_name' => 'order',
	'order_by_metadata' => array(
		'name' => 'due',
		'as' => 'integer',
		'direction' => 'asc'
	),
	'pagination' => false,
	'show_assignee' => true,
	'show_due' => true
);

if ($container instanceof ElggGroup) {
	
	if ($widget->list_guid) {
		// show from one list
		$options['container_guid'] = (int) $widget->list_guid;
	} else {
		// show from all lists
		$dbprefix = elgg_get_config('dbprefix');
		
		$options['joins'] = array("JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid");
		$options['wheres'] = array("ce.container_guid = {$widget->getOwnerGUID()}");
	}
}

$list = elgg_list_entities_from_metadata($options);
if (empty($list)) {
	$list = elgg_echo('todos:widget:due:none');
}

echo $list;