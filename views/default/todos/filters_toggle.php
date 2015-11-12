<?php

$shown = (bool) elgg_extract('shown', $vars, false);

$text = '<span>' . elgg_echo('todos:filters_toggle:show') . '</span>';
$text .= '<span class="hidden">' . elgg_echo('todos:filters_toggle:hide') . '</span>';

echo '<div class="float-alt">';
echo elgg_view('output/url', array(
	'text' => $text,
	'href' => '#todos-filters',
	'rel' => 'toggle',
	'id' => 'todos-filters-toggle',
	'class' => !empty($shown) ? 'elgg-state-active' : '',
));
echo '</div>';
echo '<div class="clearfix"></div>';
