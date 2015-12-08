<?php

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

$options = todos_get_open_assigned_item_options($widget->getOwnerGUID());
$options['limit'] = $num_display;
$options['pagination'] = false;

$list = elgg_list_entities_from_metadata($options);
if (empty($list)) {
	$list = elgg_echo('todos:assigned:no_results');
}

echo $list;