<?php

$widget = elgg_extract('entity', $vars);

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