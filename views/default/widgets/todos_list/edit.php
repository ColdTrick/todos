<?php

$widget = elgg_extract('entity', $vars);

$dbprefix = elgg_get_config('dbprefix');

$options = array(
	'type' => 'object',
	'subtype' => TodoList::SUBTYPE,
	'limit' => false,
	'container_guid' => $widget->getOwnerGUID(),
	'metadata_name_value_pairs' => array('active' => true),
	'joins' => array("JOIN {$dbprefix}objects_entity oe ON e.guid = oe.guid"),
	'order_by' => 'oe.title ASC'
);
$batch = new ElggBatch('elgg_get_entities_from_metadata', $options);

$selector = array(
	'' => elgg_echo('todos:widget:list:select')
);
foreach ($batch as $list) {
	$selector[$list->getGUID()] = $list->title;
}

echo '<div>';
echo elgg_echo('todos:widget:list:list');
echo elgg_view('input/dropdown', array(
	'name' => 'params[list_guid]',
	'value' => $widget->list_guid,
	'options_values' => $selector,
	'class' => 'mls'
));
echo '</div>';