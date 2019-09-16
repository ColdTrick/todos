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
	'metadata_name' => 'order',
	'order_by_metadata' => [
		'name' => 'due',
		'as' => 'integer',
		'direction' => 'asc',
	],
	'pagination' => false,
	'list_class' => 'todos-list',
	'show_due' => true,
	'joins' => ["JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid"],
	'wheres' => ["ce.container_guid = {$page_owner->guid}"],
]);

if (empty($list)) {
	return;
}

echo elgg_view_module('aside', elgg_echo('todos:sidebar:todoitems_due:title'), $list);
