<?php

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $num_display,
	'full_view' => false,
	'order_by_metadata' => array(
		'name' => 'completed',
		'as' => 'integer',
		'direction' => 'desc'
	),
	'pagination' => false
);

if (elgg_instanceof($widget->getOwnerEntity(), 'group')) {
	$dbprefix = elgg_get_config('dbprefix');
	
	$options['joins'] = array("JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid");
	$options['wheres'] = array("ce.container_guid = {$widget->getOwnerGUID()}");
}

$list = elgg_list_entities_from_metadata($options);
if (empty($list)) {
	$list = elgg_echo('todos:widget:closed:none');
}

echo $list;