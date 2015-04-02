<?php

$entity = elgg_extract('entity', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

$title = '';
$assignee = null;
$due = null;

if (!empty($entity)) {
	$title = $entity->title;
	$assignee = $entity->assignee;
	$due = $entity->due;
	$container_guid = $entity->getContainerGUID();
	
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $entity->getGUID()
	));
}

echo elgg_view('input/hidden', array(
	'value' => $container_guid,
	'name' => 'container_guid'
));

echo elgg_view('input/text', array(
	'value' => $title,
	'name' => 'title',
	'required' => true,
	'placeholder' => elgg_echo('todos:todoitem:title')
));

echo '<div>';

$list = get_entity($container_guid);
if (!empty($list) && elgg_instanceof($list->getContainerEntity(), 'group')) {
	echo '<label>' . elgg_echo('todos:todoitem:assignee') . '</label>';
	echo '<span class="ui-front">';
	echo elgg_view('input/userpicker', array(
		'value' => $assignee,
	));
	echo '</span>';
}

echo '<label>' . elgg_echo('todos:todoitem:due') . '</label>';
echo elgg_view('input/date', array(
	'value' => $due,
	'name' => 'due',
	'timestamp' => true
));
echo '</div>';

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
