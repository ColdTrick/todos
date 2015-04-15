<?php

$entity = elgg_extract('entity', $vars);

$content = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $entity->getGUID()
));

$content .= '<div>';
$content .= '<label>' . elgg_echo('todos:todoitem:attachment');
$content .= elgg_view('input/file', array(
	'name' => 'attachment'
));
$content .=  '</label>';
$content .= '</div>';

$content .= '<div class="elgg-foot mtm">';
$content .= elgg_view('input/submit', array('value' => elgg_echo('upload')));
$content .= '</div>';

echo elgg_view_module('info', elgg_echo('todos:todoitem:attach', array($entity->title)), $content);