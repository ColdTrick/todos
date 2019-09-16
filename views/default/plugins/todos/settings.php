<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('todos:settings:enable_personal'),
	'name' => 'params[enable_personal]',
	'checked' => $plugin->enable_personal === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('todos:settings:enable_groups'),
	'name' => 'params[enable_groups]',
	'checked' => $plugin->enable_groups === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);
