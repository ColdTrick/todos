<?php

$widget = elgg_extract('entity', $vars);

$list_guid = (int) $widget->list_guid;
if (empty($list_guid)) {
	echo elgg_echo('todos:widget:list:no_list');
	return;
}

$list = get_entity($list_guid);
if (empty($list) || !elgg_instanceof($list, 'object', TodoList::SUBTYPE)) {
	echo elgg_echo('todos:widget:list:no_list');
	return;
}

echo elgg_view_entity($list, array('full_view' => true));