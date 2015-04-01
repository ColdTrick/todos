<?php

$limit = (int) elgg_extract('limit', $vars, 5);
$page_owner = elgg_get_page_owner_entity();

$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'metadata_name' => 'order',
	'order_by_metadata' => array(
		'name' => 'due',
		'as' => 'integer',
		'direction' => 'asc'
	),
	'pagination' => false,
	'list_class' => 'todos-list',
	'show_due' => true
);

if (!empty($page_owner) && elgg_instanceof($page_owner, 'group')) {
	$dbprefix = elgg_get_config('dbprefix');

	$options['joins'] = array("JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid");
	$options['wheres'] = array("ce.container_guid = {$page_owner->getGUID()}");
}

$list = elgg_list_entities_from_metadata($options);
if (empty($list)) {
	return;
	$list = elgg_echo('todos:sidebar:todoitems_due:none');
}

echo elgg_view_module('aside', elgg_echo('todos:sidebar:todoitems_due:title'), $list);