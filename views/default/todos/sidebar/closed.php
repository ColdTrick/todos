<?php

$limit = (int) elgg_extract('limit', $vars, 5);
$page_owner = elgg_get_page_owner_entity();

if (empty($page_owner) || !(elgg_instanceof($page_owner, 'group'))) {
	return;
}

$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'order_by_metadata' => array(
		'name' => 'completed',
		'as' => 'integer',
		'direction' => 'desc'
	),
	'pagination' => false,
	'list_class' => 'todos-list'
);

if (!empty($page_owner) && elgg_instanceof($page_owner, 'group')) {
	$dbprefix = elgg_get_config('dbprefix');
	
	$options['joins'] = array("JOIN {$dbprefix}entities ce ON e.container_guid = ce.guid");
	$options['wheres'] = array("ce.container_guid = {$page_owner->getGUID()}");
}

$list = elgg_list_entities_from_metadata($options);
if (empty($list)) {
	return;
	$list = elgg_echo('todos:sidebar:todoitems_closed:none');
}

echo elgg_view_module('aside', elgg_echo('todos:sidebar:todoitems_closed:title'), $list);