<?php

$widget = elgg_extract('entity', $vars);

$list_guid = (int) $widget->list_guid;
if (empty($list_guid)) {
	echo elgg_echo('todos:widget:list:no_list');
	return;
}

$list = get_entity($list_guid);
if (!$list instanceof \TodoList) {
	echo elgg_echo('todos:widget:list:no_list');
	return;
}

$list_completed = (bool) $widget->list_completed;

echo elgg_view_entity($list, [
	'full_view' => true,
	'list_completed' => $list_completed,
]);
