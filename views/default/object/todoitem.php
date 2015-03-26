<?php

$full = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars);

if (!$full) {
	$checkbox = elgg_view('input/checkbox', array(
		'rel' => $entity->guid,
		'checked' => $entity->isCompleted()
	));
	
	$body = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL()
	));
	
	$body .= elgg_view_menu('todoitem', array(
		'entity' => $entity, 
		'class' => 'elgg-menu-hz elgg-menu-todos', 
		'sort_by' => 'register'
	));

	echo elgg_view('page/components/image_block', array('image' => $checkbox, 'body' => $body));
} else {
	echo elgg_view_comments($entity, true);
}
