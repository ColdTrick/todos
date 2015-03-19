<?php

$entity = elgg_extract('entity', $vars);

$title = '';
if ($entity) {
	$title = $entity->title;	
}

echo elgg_view('input/text', array(
	'value' => $title, 
	'name' => 'title', 
	'placeholder' => elgg_echo('todos:todolist:title'),
	'required' => true,
	'class' => 'mvm'
));
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
