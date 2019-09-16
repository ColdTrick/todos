<?php

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

$options = todos_get_open_assigned_item_options($widget->getOwnerGUID());
$options['limit'] = $num_display;
$options['pagination'] = false;
$options['no_results'] = elgg_echo('todos:assigned:no_results');

echo elgg_list_entities($options);
