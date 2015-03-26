<?php

$limit = (int) elgg_extract('limit', $vars, 5);

$options = array(
	'type' => 'object',
	'subtype' => TodoItem::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'order_by_metadata' => array(
		'name' => 'completed',
		'as' => 'integer',
		'direction' => 'desc'
	)
);
$list = elgg_list_entities_from_metadata($options);
if (empty($list)) {
	$list = elgg_echo('todos:sidebar:todoitems_closed:none');
}

echo elgg_view_module('aside', elgg_echo('todos:sidebar:todoitems_closed:title'), $list);