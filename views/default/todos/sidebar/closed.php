<?php

$limit = (int) elgg_extract('limit', $vars, 5);
$page_owner = elgg_get_page_owner_entity();

if (!$page_owner instanceof \ElggGroup) {
	return;
}

$dbprefix = elgg_get_config('dbprefix');

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'order_by_metadata' => [
		'name' => 'completed',
		'as' => 'integer',
		'direction' => 'desc',
	],
	'pagination' => false,
	'list_class' => 'todos-list',
	'joins' => ["JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid"],
	'wheres' => ["ce.container_guid = {$page_owner->guid}"],
]);

if (empty($list)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('todos:sidebar:todoitems_closed:title'), $list);
