<?php

$plugin = elgg_extract('entity', $vars);

$noyes_options = array(
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes')
);

echo '<div>';
echo elgg_echo('todos:settings:enable_personal');
echo elgg_view('input/dropdown', array(
	'name' => 'params[enable_personal]',
	'value' => $plugin->enable_personal,
	'options_values' => $noyes_options,
	'class' => 'mls'
));
echo '</div>';

echo '<div>';
echo elgg_echo('todos:settings:enable_groups');
echo elgg_view('input/dropdown', array(
	'name' => 'params[enable_groups]',
	'value' => $plugin->enable_groups,
	'options_values' => $noyes_options,
	'class' => 'mls'
));
echo '</div>';