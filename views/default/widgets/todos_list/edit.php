<?php

$widget = elgg_extract('entity', $vars);

$batch = elgg_get_entities([
	'type' => 'object',
	'subtype' => \TodoList::SUBTYPE,
	'limit' => false,
	'container_guid' => $widget->getOwnerGUID(),
	'metadata_name_value_pairs' => ['active' => true],
	'order_by_metadata' => [
		'name' => 'title',
		'direction' => 'ASC',
	],
	'batch' => true,
]);

$selector = [
	'' => elgg_echo('todos:widget:list:select')
];
foreach ($batch as $list) {
	$selector[$list->guid] = $list->getDisplayName();
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('todos:widget:list:list'),
	'name' => 'params[list_guid]',
	'value' => $widget->list_guid,
	'options_values' => $selector,
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('todos:widget:list:list_completed'),
	'name' => 'params[list_completed]',
	'value' => 1,
	'checked' => (bool) $widget->list_completed,
]);
