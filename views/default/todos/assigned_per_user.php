<?php

$user = elgg_extract('user', $vars);
$items = elgg_extract('entities', $vars);

if (!($user instanceof ElggUser) || empty($items)) {
	return;
}

ksort($items);

$view_options = array(
	'limit' => false,
	'offset' => 0,
	'full_view' => false,
	'show_assignee' => false,
	'item_class' => 'todos-list-item',
);

$content = elgg_view_entity_list($items, $view_options);

echo elgg_view_module('info', $user->name, $content);
