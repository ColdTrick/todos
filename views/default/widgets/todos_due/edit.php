<?php

$widget = elgg_extract('entity', $vars);
$container = $widget->getContainerEntity();

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 10;
}

echo '<div>';
echo elgg_echo('widget:numbertodisplay');
echo elgg_view('input/dropdown', array(
	'name' => 'params[num_display]',
	'value' => $num_display,
	'options' => range(1, 25),
	'class' => 'mls'
));
echo '</div>';

if ($container instanceof ElggGroup) {
	
	$list_options = [
		0 => elgg_echo('todos:widget:due:list_select:all'),
	];
	
	$batch = elgg_get_entities([
		'type' => 'object',
		'subtype' => TodoList::SUBTYPE,
		'container_guid' => $container->guid,
		'limit' => false,
		'order_by_metadata' => array(
			'name' => 'order',
			'direction' => 'ASC',
			'as' => 'integer',
		),
		'metadata_name_value_pairs' => array(
			'active' => true,
		),
		'batch' => true,
	]);
	foreach ($batch as $list) {
		$list_options[$list->guid] = $list->getDisplayName();
	}
	
	// check if selected list is in options, because the list could have been closed
	if ($widget->list_guid) {
		if (!isset($list_options[$widget->list_guid])) {
			$list = get_entity($widget->list_guid);
			if ($list instanceof TodoList) {
				$list_options[$list->guid] = $list->getDisplayName();
			}
		}
	}
	
	echo '<div>';
	echo elgg_echo('todos:widget:due:list_select');
	echo elgg_view('input/dropdown', array(
		'name' => 'params[list_guid]',
		'value' => (int) $widget->list_guid,
		'options_values' => $list_options,
		'class' => 'mls'
	));
	echo '</div>';
}